<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

        try {

            $userInfo = $this->createUser(
                [
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'uid' => uniqid()
                ]
            );

        } catch (\Exception $e) {

            return response()->json(
                [
                    'errorMessage' => 'Error creating user',
                    'errors' => ['An error occurred']
                ],
                422
            );

        }

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
                    'status' => USER_STATUS_ACTIVE
                ]
            );

            if (!$isAuthorized) {
                throw new Exception('Authentification failed!');
            }

        } catch (\Exception $e) {

            return response()->json(
                [
                    'errorMessage' => $e->getMessage(),
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
                    'errorMessage' => 'Can not log out current user',
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
     * Create user
     *
     * @param array $userInfo
     *
     * @return array
     */
    protected function createUser($userInfo) {
        $referrer = Session::get('referrer');

        if (!is_null($referrer)) {
            $userInfo['referrer'] = $referrer;
        }

        return User::create($userInfo);
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
            case USER_ROLE_ADMIN:
                return '/admin';

            case USER_ROLE_USER:
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
                        'status' => USER_STATUS_ACTIVE,
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
                    'errorMessage' => $e->getMessage(),
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
                        'status' => USER_STATUS_ACTIVE,
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
                    'errorMessage' => $e->getMessage(),
                    'errors' => ['Can not authorize with Facebook']
                ],
                422
            );
        }

        return redirect()->action('UserController@profile');
    }

}
