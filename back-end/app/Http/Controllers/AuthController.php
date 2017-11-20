<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
    protected function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {

            $userInfo = User::create(
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
     * Send activation email to the user
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function activate(Request $request)
    {
        $userID = $request->input('uid', '');

        $user = User::where('uid', $userID)->first();;

        if ($user) {
            $user->status = USER_STATUS_ACTIVE;
            $user->save();
        }


        // return page about successful activation
        return response()->json([]);
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
     * Login user with Facebook
     *
     * @param Request $request
     *
     * @return json
     */
    public function fbLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'id' => 'required|string|max:255'
        ]);

        $email = $request->email;
        $fbID = $request->id;


        try {
            $user = User::where('email', $email)->first();

            if (!$user) {
                // register a new User
                $userInfo = User::create(
                    [
                        'email' => $email,
                        'password' => bcrypt(uniqid()),
                        'uid' => uniqid(),
                        'status' => USER_STATUS_ACTIVE,
                        'fbid' => $fbID
                    ]
                );

                $userID = $userInfo['id'];
            } else {
                $userID = $user->id;

                if (is_null($user->fbid)) {
                    $user->fbid = $fbID;
                    $user->save();
                }
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

        return response()->json(
            [
                'userPage' => $this->getUserPage(Auth::user()->role)
            ]
        );
    }

    /**
     * Login user with Google
     *
     * @param Request $request
     *
     * @return json
     */
    public function gLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'id' => 'required|string|max:255'
        ]);

        $email = $request->email;
        $fbID = $request->id;


        try {
            $user = User::where('email', $email)->first();

            if (!$user) {
                // register a new User
                $userInfo = User::create(
                    [
                        'email' => $email,
                        'password' => bcrypt(uniqid()),
                        'uid' => uniqid(),
                        'status' => USER_STATUS_ACTIVE,
                        'gid' => $fbID
                    ]
                );

                $userID = $userInfo['id'];
            } else {
                $userID = $user->id;

                if (is_null($user->gid)) {
                    $user->fbid = $fbID;
                    $user->save();
                }
            }

            $isAuthorized = Auth::loginUsingId($userID);

            if (!$isAuthorized) {
                throw new Exception('Authentification failed.');
            }

        } catch (\Exception $e) {

            return response()->json(
                [
                    'errorMessage' => $e->getMessage(),
                    'errors' => ['Can not authorize with Google']
                ],
                422
            );
        }

        return response()->json(
            []
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
}
