<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Exceptions\CaptchaException;
use App\Models\Services\AccountsService;
use App\Models\Services\AuthService;
use App\Models\Services\CaptchaService;
use App\Models\Services\ResetPasswordsService;
use App\Models\Services\VerificationService;
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
                'email' => 'required|email|max:255|unique:users|bail',
                'password' => 'required|string|min:6|max:32|bail',
                'password_confirmation' => 'required_with:password|same:password|bail',
                'captcha' => 'required|string|bail'
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                    'password' => 'Password',
                    'password_confirmation' => 'Password Confirmation',
                    'captcha' => 'Captcha'
                ],
                [
                    'email.unique' => 'User with such Email already registered',
                    'password.min' => 'The Password field must be at least 6 characters',
                    'password_confirmation.required_with' => 'The Password Confirmation does not match',
                    'password_confirmation.same' => 'The Password Confirmation does not match',
                    'captcha.required' => 'Invalid captcha. Please try again.',
                ]
            )
        );

        DB::beginTransaction();

        try {

            CaptchaService::checkCaptcha($request->captcha);

            $uid = AccountsService::registerUser($request->email, $request->password);

        } catch (\Exception $e) {

            DB::rollback();

            $message = 'Registration failed';
            $status = 422;

            if ($e instanceof CaptchaException) {
                $message = $e->getMessage();
                $status = 500;
            }

            return response()->json(
                [
                    'message' => 'Error creating user',
                    'errors' => [
                        'captcha' => $message
                    ]
                ],
                $status
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
                'email' => 'required|email|max:255|bail',
                'password' => 'required|bail'
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
                'email' => 'required|email|max:255|bail',
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
                'token' => 'required|string|bail',
                'password' => 'required|string|min:6|max:32|bail',
                'password_confirmation' => 'required_with:password|same:password|bail',
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
        DB::beginTransaction();

        try {

            $redirectUrl = AuthService::loginWithFacebook();

        } catch (\Exception $e) {

            DB::rollback();

            $redirectUrl = '/';

        }

        DB::commit();

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
        DB::beginTransaction();

        try {

            $redirectUrl = AuthService::loginWithGoogle();

        } catch (\Exception $e) {

            DB::rollback();

            $redirectUrl = '/';

        }

        DB::commit();

        return redirect($redirectUrl);
    }

    /**
     * Process verification response
     *
     * @param Request $request
     *
     * @return json
     */
    public function trackVerifyResponse(Request $request)
    {
        DB::beginTransaction();

        try {

            $requestParams = $request->json()->all();

            $citizenship =  $requestParams['verification']['person']['citizenship'] ?? '';
            $nationality =  $requestParams['verification']['person']['nationality'] ?? '';

            $apiResponse = [
                'signature' => $request->header('x-signature'),
                'session_id' => $requestParams['verification']['id'],
                'response_status' => $requestParams['verification']['status'],
                'response_code' => $requestParams['verification']['code'],
                'fail_reason' => $requestParams['verification']['reason'],
                'acceptance_time' => $requestParams['verification']['acceptanceTime'],
                'citizenship' => ($citizenship !== '') ? $citizenship : $nationality
            ];

            VerificationService::trackVerificationResponse($requestParams['status'], $apiResponse);

        } catch (\Exception $e) {

            DB::rollback();

        }

        DB::commit();

        return response()->json(
            []
        );
    }

}
