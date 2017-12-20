<?php

namespace App\Http\Controllers;

use App\Mail\ActivateAccount;
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


class AuthController extends Controller
{
    /**
     * AuthController constructor.
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
                    'password' => bcrypt($request->password),
                    'uid' => uniqid()
                ]
            );

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error creating user',
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
     * @return redirect to FB page
     */
    public function toFacebookProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Provide callback from facebook
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
                        'password' => bcrypt(uniqid()),
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

    public function toGoogleProvider()
    {
        return Socialite::driver('google')->redirect();
    }

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
                        'password' => bcrypt(uniqid()),
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
