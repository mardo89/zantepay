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

            if ($e instanceof CaptchaException || $e instanceof AuthException) {
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
                'password' => 'required|bail',
	            'captcha' => 'required|string|bail'
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                    'password' => 'Password',
	                'captcha' => 'Captcha'
                ],
                [
                    'email.required' => 'Enter email',
                    'password.required' => 'Enter password',
	                'captcha.required' => 'Invalid captcha. Please try again.',
                ]
            )

        );

        try {

            CaptchaService::checkCaptcha($request->captcha);

            $auth = AuthService::loginUser($request->email, $request->password);

        } catch (\Exception $e) {

            $message = 'Authentification failed';
            $status = 422;

            if ($e instanceof CaptchaException || $e instanceof AuthException) {
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

        return response()->json(
            $auth
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
	            'captcha' => 'required|string|bail'
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
	                'captcha' => 'Captcha'
                ],
                [
	                'captcha.required' => 'Invalid captcha. Please try again.',
                ]
            )
        );

        DB::beginTransaction();

        try {

	        CaptchaService::checkCaptcha($request->captcha);

            AccountsService::resetPassword($request->email);

        } catch (\Exception $e) {

            DB::rollback();

	        $message = 'Error restoring password';
	        $status = 422;

	        if ($e instanceof CaptchaException) {
		        $message = $e->getMessage();
		        $status = 500;
	        }

	        return response()->json(
		        [
			        'message' => $message,
			        'errors' => [
				        'captcha' => $message
			        ]
		        ],
		        $status
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

            file_put_contents('/tmp/veriff.me', json_encode($requestParams) . PHP_EOL . PHP_EOL, FILE_APPEND);

            $citizenship =  $requestParams['verification']['person']['citizenship'] ?? '';
            $nationality =  $requestParams['verification']['person']['nationality'] ?? '';

            $apiResponse = [
                'signature' => $request->header('x-signature'),
                'session_id' => $requestParams['verification']['id'],
                'response_status' => $requestParams['verification']['status'],
                'response_code' => $requestParams['verification']['code'],
                'fail_reason' => $requestParams['verification']['reason'],
                'acceptance_time' => $requestParams['verification']['acceptanceTime'],
                'country' => ($citizenship !== '') ? $citizenship : $nationality
            ];

            VerificationService::trackVerificationResponse($requestParams['status'], $apiResponse);

        } catch (\Exception $e) {

            DB::rollback();

            exit($e->getMessage());
        }

        DB::commit();

        return response()->json(
            []
        );
    }

}
