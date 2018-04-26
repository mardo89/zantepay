<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Models\Services\AccountsService;
use App\Models\Services\AuthService;
use App\Models\Services\ResetPasswordsService;
use App\Models\Validation\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;


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
                'password' => 'required|string|min:6',
                'password_confirmation' => 'required_with:password|same:password',
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                    'password' => 'Password',
                    'password_confirmation' => 'Password Confirmation'
                ],
                [
                    'email.unique' => 'User with such Email already registered',
                    'password.min' => 'The Password field must be at least 6 characters',
                    'password_confirmation.required_with' => 'The Password Confirmation does not match',
                    'password_confirmation.same' => 'The Password Confirmation does not match',
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
                        'password_confirmation' => 'Registration failed'
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

            $userPage = AuthService::loginUser($request->email, $request->password);

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
                'userPage' => $userPage
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
                'email' => 'required|string|email|max:255',
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                ]
            )
        );

        DB::beginTransaction();

        try {

            AccountsService::resetPassword($request->email);

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
                'token' => 'required|string',
                'password' => 'required|string|min:6',
                'password_confirmation' => 'required_with:password|same:password',
            ],
            ValidationMessages::getList(
                [
                    'token' => 'Token',
                    'password' => 'Password',
                    'password_confirmation' => 'Password Confirmation',
                ],
                [
                    'password.min' => 'The Password field must be at least 6 characters',
                    'password_confirmation.required_with' => 'The Password Confirmation does not match',
                    'password_confirmation.same' => 'The Password Confirmation does not match',
                ]
            )
        );

        DB::beginTransaction();

        try {

            AccountsService::savePassword($request->token, $request->password);

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

            $redirectUrl = AuthService::loginWithFacebook();

        } catch (\Exception $e) {

            $redirectUrl = '/';

        }

        return redirect($redirectUrl);
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

            $redirectUrl = AuthService::loginWithGoogle();

        } catch (\Exception $e) {

            $redirectUrl = '/';

        }

        return redirect($redirectUrl);
    }

}
