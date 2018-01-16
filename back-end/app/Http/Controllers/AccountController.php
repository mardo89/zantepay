<?php

namespace App\Http\Controllers;

use App\Mail\ActivateAccount;
use App\Mail\ResetPassword;
use App\Models\DB\PasswordReset;
use App\Models\DB\Profile;
use App\Models\DB\SocialNetworkAccount;
use App\Models\DB\Verification;
use App\Models\DB\Wallet;
use App\Models\DB\User;
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

        $email = $request->input('email');
        $password = $request->input('password');

        DB::beginTransaction();

        try {

            $userInfo = $this->createUser(
                [
                    'email' => $email,
                    'password' => User::hashPassword($password),
                    'uid' => uniqid()
                ]
            );

            Mail::to($userInfo['email'])->send(new ActivateAccount($userInfo['uid']));

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
                'uid' => $userInfo['uid']
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

        $email = $request->input('email', '');
        $password = $request->input('password', '');

        try {
            $isAuthorized = Auth::attempt(
                [
                    'email' => $email,
                    'password' => $password,
                ]
            );

            if (!$isAuthorized) {
                throw new AuthenticationException('Login or password incorrect');
            }

            if (Auth::user()->status === User::USER_STATUS_INACTIVE) {
                throw new AuthenticationException('Your account is disabled');
            }

        } catch (\Exception $e) {

            $message = ($e instanceof AuthenticationException) ? $e->getMessage() : 'Authentification failed';

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

            Auth::logout();

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
                'email' => 'required|string|email|max:255',
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
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

            Mail::to($resetInfo['email'])->send(new ResetPassword($resetInfo['token']));

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
    protected function createUser($userInfo)
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

            case User::USER_ROLE_USER:
                return '/user/profile';

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

            $snAccount = SocialNetworkAccount::where('user_token', $snUser->token)
                ->where('social_network_id', SocialNetworkAccount::SOCIAL_NETWORK_FACEBOOK)
                ->first();

            if (!$snAccount) {

                // register a new User
                $userInfo = $this->createUser(
                    [
                        'email' => $snUser->email,
                        'password' => User::hashPassword(uniqid()),
                        'uid' => uniqid(),
                        'status' => User::USER_STATUS_NOT_VERIFIED,
                        'avatar' => $snUser->avatar,
                    ]
                );

                //create social network account
                SocialNetworkAccount::create(
                    [
                        'social_network_id' => SocialNetworkAccount::SOCIAL_NETWORK_FACEBOOK,
                        'user_token' => $snUser->token,
                        'user_id' => $userInfo['id']
                    ]
                );

                $userID = $userInfo['id'];

            } else {

                $userID = $snAccount->user()->id;

            }

            $isAuthorized = Auth::loginUsingId($userID);

            if (!$isAuthorized) {
                throw new Exception('Authentification failed.');
            }

        } catch (\Exception $e) {

            return redirect()->action('IndexController@main');

        }

        return redirect()->action('UserController@profile');
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

            $snAccount = SocialNetworkAccount::where('user_token', $snUser->token)
                ->where('social_network_id', SocialNetworkAccount::SOCIAL_NETWORK_GOOGLE)
                ->first();

            if (!$snAccount) {

                // register a new User
                $userInfo = $this->createUser(
                    [
                        'email' => $snUser->email,
                        'password' => User::hashPassword(uniqid()),
                        'uid' => uniqid(),
                        'status' => User::USER_STATUS_NOT_VERIFIED,
                        'avatar' => $snUser->avatar,
                    ]
                );

                //create social network account
                SocialNetworkAccount::create(
                    [
                        'social_network_id' => SocialNetworkAccount::SOCIAL_NETWORK_GOOGLE,
                        'user_token' => $snUser->token,
                        'user_id' => $userInfo['id']
                    ]
                );

                $userID = $userInfo['id'];

            } else {

                $userID = $snAccount->user()->id;

            }

            $isAuthorized = Auth::loginUsingId($userID);

            if (!$isAuthorized) {
                throw new Exception('Authentification failed.');
            }

        } catch (\Exception $e) {

            return redirect()->action('IndexController@main');

        }

        return redirect()->action('UserController@profile');
    }

}
