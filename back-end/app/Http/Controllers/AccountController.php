<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Mail\ActivateAccount;
use App\Mail\ChangePassword;
use App\Mail\ResetPassword;
use App\Models\DB\ExternalRedirect;
use App\Models\DB\PasswordReset;
use App\Models\DB\Profile;
use App\Models\DB\SocialNetworkAccount;
use App\Models\DB\Verification;
use App\Models\DB\Wallet;
use App\Models\DB\User;
use App\Models\Services\AccountsService;
use App\Models\Services\AuthService;
use App\Models\Services\MailService;
use App\Models\Validation\ValidationMessages;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Mockery\Exception;


class AccountController extends Controller
{
    /**
     * AccountController constructor.
     */
    public function __construct()
    {
    }

    /**
     * Register users
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function register(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                    'password' => 'Password'
                ],
                [
                    'email.unique' => 'User with such Email already registered',
                    'password.min' => 'The Password field must be at least 6 characters',
                    'password.confirmed' => 'The Password confirmation does not match',
                ]
            )
        );

        DB::beginTransaction();

        try {

            $uid = AccountsService::registerUser($request->email, $request->password);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error creating user',
                    'errors' => [
                        'email' => '',
                        'password' => '',
                        'confirm-password' => 'Registration failed'
                    ]
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            [
                'uid' => $uid
            ]
        );

    }

    /**
     * Login user
     *
     * @param Request $request
     *
     * @return json
     */
    public function login(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|email|max:255',
                'password' => 'required'
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                    'password' => 'Password',
                ],
                [
                    'email.required' => 'Enter email',
                    'password.required' => 'Enter password',
                ]
            )

        );

        try {

            AuthService::loginUser($request->email, $request->password);

        } catch (\Exception $e) {

            $message = ($e instanceof AuthException) ? $e->getMessage() : 'Authentification failed';

            return response()->json(
                [
                    'message' => $message,
                    'errors' => [
                        'email' => '',
                        'password' => $message
                    ]
                ],
                422
            );
        }

        return response()->json(
            [
                'userPage' => $this->getUserPage(Auth::user()->role)
            ]
        );
    }

    /**
     * Logout user
     */
    public function logout()
    {
        try {

            AuthService::logoutUser();

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Can not log out current user',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            [
                'userPage' => '/'
            ]
        );

    }

    /**
     * Send email with password reset link
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function resetPassword(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|string|email|max:255|exists:users,email',
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                ],
                [
                    'email.exists' => 'There is no user with such email'
                ]
            )
        );

        $email = $request->input('email');

        DB::beginTransaction();

        try {

            $resetInfo = PasswordReset::create(
                [
                    'email' => $email,
                    'token' => uniqid()
                ]
            );

            MailService::sendResetPasswordEmail($resetInfo['email'], $resetInfo['token']);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Can not restore password',
                    'errors' => [
                        'email' => 'Error restoring password'
                    ]
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            []
        );

    }

    /**
     * Save new user password
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function savePassword(Request $request)
    {
        $this->validate(
            $request,
            [
                'password' => 'required|string|min:6|confirmed',
                'token' => 'required|string'
            ],
            ValidationMessages::getList(
                [
                    'password' => 'Password',
                    'token' => 'Token',
                ],
                [
                    'password.min' => 'The Password field must be at least 6 characters',
                    'password.confirmed' => 'The Password confirmation does not match',
                ]
            )
        );

        $resetInfo = PasswordReset::where('token', $request->token)
            ->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 15 MINUTE)'))
            ->first();

        if (is_null($resetInfo)) {
            return response()->json(
                [
                    'nextStep' => action('IndexController@resetPassword')
                ]
            );
        }

        $user = User::where('email', $resetInfo->email)->first();

        if (is_null($user)) {
            return response()->json(
                [
                    'nextStep' => action('IndexController@resetPassword')
                ]
            );
        }

        DB::beginTransaction();

        try {

            $user->password = User::hashPassword($request->password);
            $user->save();

            PasswordReset::where('email', $user->email)->delete();

            MailService::sendChangePasswordEmail($user->email);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'nextStep' => action('IndexController@resetPassword')
                ]
            );
        }

        DB::commit();

        return response()->json(
            [
                'nextStep' => action('IndexController@confirmPasswordReset')
            ]
        );
    }

    /**
     * Create user with profile and wallet
     *
     * @param array $userInfo
     *
     * @return array
     */
    public function createUser($userInfo)
    {
        $referrer = Session::get('referrer');

        if (!is_null($referrer)) {
            $userInfo['referrer'] = $referrer;
        }

        $user = User::create($userInfo);

        Profile::create(
            [
                'user_id' => $user['id']
            ]
        );

        Verification::create(
            [
                'user_id' => $user['id']
            ]
        );

        Wallet::create(
            [
                'user_id' => $user['id']
            ]
        );

        Session::forget('referrer');

        return $user;
    }

    /**
     * Return user home page
     *
     * @param int $userRole
     *
     * @return string
     */
    protected function getUserPage($userRole)
    {
        switch ($userRole) {
            case User::USER_ROLE_ADMIN:
                return '/admin/users';

            case User::USER_ROLE_MANAGER:
                return '/admin/users';

            case User::USER_ROLE_SALES:
                return '/admin/users';

            case User::USER_ROLE_USER:
                return '/user/wallet';

            default:
                return '/';
        }
    }

    /**
     * Redirect to Facebook
     * @return redirect
     */
    public function toFacebookProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Provide callback from Facebook
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     */
    public function FacebookProviderCallback()
    {

        try {

            $snUser = Socialite::driver('facebook')->user();

            $userNameParts = explode(' ', $snUser->getName());

            $snAccount = SocialNetworkAccount::where('user_token', $snUser->getId())
                ->where('social_network_id', SocialNetworkAccount::SOCIAL_NETWORK_FACEBOOK)
                ->first();

            if (!$snAccount) {

                $userInfo = User::where('email', $snUser->email)->first();

                if (!$userInfo) {

                    // register a new User
                    $userInfo = $this->createUser(
                        [
                            'email' => $snUser->email,
                            'password' => User::hashPassword(uniqid()),
                            'uid' => uniqid(),
                            'status' => User::USER_STATUS_PENDING,
                            'first_name' => $userNameParts[1] ?? "",
                            'last_name' => $userNameParts[0] ?? "",
                            'avatar' => $snUser->avatar,
                        ]
                    );

                }

                //create social network account
                SocialNetworkAccount::create(
                    [
                        'social_network_id' => SocialNetworkAccount::SOCIAL_NETWORK_FACEBOOK,
                        'user_token' => $snUser->getId(),
                        'user_id' => $userInfo->id
                    ]
                );

                $userID = $userInfo['id'];

            } else {

                $userID = $snAccount->user->id;

            }

            $isAuthorized = Auth::loginUsingId($userID);

            if (!$isAuthorized) {
                throw new Exception('Authentification failed.');
            }

        } catch (\Exception $e) {

            return redirect()->action('IndexController@main');

        }

        return redirect(
            $this->getUserPage(
                Auth::user()->role
            )
        );
    }

    /**
     * Redirect to Google
     *
     * @return redirect
     */
    public function toGoogleProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Provide callback from Google
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function GoogleProviderCallback()
    {

        try {

            $snUser = Socialite::driver('google')->user();

            $userNameParts = explode(' ', $snUser->getName());

            $snAccount = SocialNetworkAccount::where('user_token', $snUser->getId())
                ->where('social_network_id', SocialNetworkAccount::SOCIAL_NETWORK_GOOGLE)
                ->first();

            if (!$snAccount) {

                $userInfo = User::where('email', $snUser->email)->first();

                if (!$userInfo) {

                    // register a new User
                    $userInfo = $this->createUser(
                        [
                            'email' => $snUser->email,
                            'password' => User::hashPassword(uniqid()),
                            'uid' => uniqid(),
                            'status' => User::USER_STATUS_PENDING,
                            'first_name' => $userNameParts[0] ?? "",
                            'last_name' => $userNameParts[1] ?? "",
                            'avatar' => $snUser->avatar,
                        ]
                    );

                }

                //create social network account
                SocialNetworkAccount::create(
                    [
                        'social_network_id' => SocialNetworkAccount::SOCIAL_NETWORK_GOOGLE,
                        'user_token' => $snUser->getId(),
                        'user_id' => $userInfo->id
                    ]
                );

                $userID = $userInfo['id'];

            } else {

                $userID = $snAccount->user->id;

            }

            $isAuthorized = Auth::loginUsingId($userID);

            if (!$isAuthorized) {
                throw new Exception('Authentification failed.');
            }

        } catch (\Exception $e) {

            return redirect()->action('IndexController@main');

        }

        return redirect(
            $this->getUserPage(
                Auth::user()->role
            )
        );
    }

}
