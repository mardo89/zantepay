<?php

namespace App\Http\Controllers;

use App\Mail\ActivateAccount;
use App\Mail\ResetPassword;
use App\Models\PasswordReset;
use App\Models\Profile;
use App\Models\Verification;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\User;
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
        $this->validate($request, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        DB::beginTransaction();

        try {

            $userInfo = $this->createUser(
                [
                    'email' => $request->email,
                    'password' => User::hashPassword($request->password),
                    'uid' => uniqid()
                ]
            );

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => $e->getMessage(),//'Error creating user',
                    'errors' => ['An error occurred']
                ],
                422
            );

        }

        DB::commit();

        Mail::to($userInfo['email'])->send(new ActivateAccount($userInfo['uid']));

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
        try {
            $isAuthorized = Auth::attempt(
                [
                    'email' => $request->email,
                    'password' => $request->password,
                ]
            );

            if (!$isAuthorized) {
                throw new Exception('Authentification failed!');
            }

            if (Auth::user()->status === User::USER_STATUS_INACTIVE) {
                throw new Exception('Your account is disabled!');
            }

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'errors' => ['Login or password incorrect']
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
                    'errors' => ['Error while logging out']
                ],
                422
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
        $this->validate($request, [
            'email' => 'required|string|email|max:255',
        ]);

        $email = $request->email;

        DB::beginTransaction();

        try {
            $resetInfo = PasswordReset::create(
                [
                    'email' => $email,
                    'token' => uniqid()
                ]
            );

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Can not restore password',
                    'errors' => ['An error occurred']
                ],
                422
            );

        }

        DB::commit();

        Mail::to($request->email)->send(new ResetPassword($resetInfo['token']));

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
        $this->validate($request, [
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required|string'
        ]);

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
        $snUser = Socialite::driver('facebook')->user();

        try {
            $user = User::where('email', $snUser->email)->first();

            if (!$user) {
                list($lastName, $firstName) = explode($snUser->name, ' ');

                // register a new User
                $userInfo = $this->createUser(
                    [
                        'email' => $snUser->email,
                        'password' => User::hashPassword(uniqid()),
                        'uid' => uniqid(),
                        'status' => User::USER_STATUS_NOT_VERIFIED,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'avatar' => $snUser->avatar,
                    ]
                );

                $userID = $userInfo['id'];
            } else {
                $userID = $user->id;
            }

            $isAuthorized = Auth::loginUsingId($userID);

            if (!$isAuthorized) {
                throw new Exception('Authentification failed.');
            }

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'errors' => ['Can not authorize with Facebook']
                ],
                422
            );
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
        $snUser = Socialite::driver('google')->user();

        try {
            $user = User::where('email', $snUser->email)->first();

            if (!$user) {
                list($firstName, $lastName) = explode($snUser->name, ' ');

                // register a new User
                $userInfo = $this->createUser(
                    [
                        'email' => $snUser->email,
                        'password' => User::hashPassword(uniqid()),
                        'uid' => uniqid(),
                        'status' => User::USER_STATUS_NOT_VERIFIED,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'avatar' => $snUser->avatar,
                    ]
                );

                $userID = $userInfo['id'];
            } else {
                $userID = $user->id;
            }

            $isAuthorized = Auth::loginUsingId($userID);

            if (!$isAuthorized) {
                throw new Exception('Authentification failed.');
            }

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'errors' => ['Can not authorize with Facebook']
                ],
                422
            );
        }

        return redirect()->action('UserController@profile');
    }

}
