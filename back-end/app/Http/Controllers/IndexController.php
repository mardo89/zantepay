<?php

namespace App\Http\Controllers;

use App\Exceptions\CaptchaException;
use App\Models\Services\AccountsService;
use App\Models\Services\CaptchaService;
use App\Models\Services\FeedService;
use App\Models\Services\IcoService;
use App\Models\Services\MailService;
use App\Models\Services\RegistrationsService;
use App\Models\Services\ResetPasswordsService;
use App\Models\DB\User;
use App\Models\Services\UsersService;
use App\Models\Validation\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;


class IndexController extends Controller
{
    /**
     * Main page
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function main(Request $request)
    {
        AccountsService::setReferrer($request->ref);
        AccountsService::setExternals();
        AccountsService::trackAffiliate($request->track_id);

        return view(
            'main.index',
            [
                'menuPrefix' => '',
                'ico' => (new IcoService())->getInfo(),
                'feed' => (new FeedService())->getItems()
            ]
        );
    }

    /**
     * Save registration for Pre-Ico
     *
     * @param Request $request
     *
     * @return View
     */
    public function saveRegistration(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|email|max:255|bail',
                'amount' => 'numeric|nullable|bail'
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                    'amount' => 'Amount'
                ]
            )
        );

        DB::beginTransaction();

        try {

            RegistrationsService::registerForPreIco($request->email, $request->amount);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Registration failed',
                    'errors' => [
                        'email' => '',
                        'amount' => 'Registration failed'
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
     * Save investor
     *
     * @param Request $request
     *
     * @return View
     */
    public function saveInvestor(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|email|max:255|unique:investors|bail',
                'skype-id' => 'required|string|max:100|unique:investors,skype_id|bail',
                'first-name' => 'required|string|max:100|bail',
                'last-name' => 'required|string|max:100|bail',
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                    'skype-id' => 'Skype ID',
                    'first-name' => 'First Name',
                    'last-name' => 'Last Name',
                ],
                [
                    'email.unique' => 'Investor with such Email already registered',
                    'skype-id.unique' => 'Investor with such Skype ID already registered',
                ]
            )
        );


        DB::beginTransaction();

        try {

            $email = $request->input('email');
            $skype = $request->input('skype-id');
            $firstName = $request->input('first-name');
            $lastName = $request->input('last-name');

            RegistrationsService::becomeInvestor($email, $skype, $firstName, $lastName);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Registration failed',
                    'errors' => [
                        'skype-id' => '',
                        'first-name' => '',
                        'last-name' => '',
                        'email' => 'Registration failed'
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
     * Save user in news letters
     *
     * @param Request $request
     *
     * @return View
     */
    public function saveNewsLetter(Request $request)
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

            $email = $request->input('email');

            RegistrationsService::joinToNewsLetter($email);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Registration failed',
                    'errors' => [
                        'email' => 'Registration failed'
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
     * Confirm user invitation
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmInvitation(Request $request)
    {
        AccountsService::setReferrer($request->ref);

        return view(
            'main.confirm-invitation',
            [
                'referralToken' => $request->ref,
                'captcha' => env('CAPTCHA_KEY')
            ]
        );
    }

    /**
     * Reset user password
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        try {
            $resetToken = $request->input('rt', '');

            ResetPasswordsService::checkPasswordReset($resetToken);

        } catch (\Exception $e) {

            return view('main.reset-password-fail');

        }

        return view(
            'main.reset-password-success',
            [
                'resetToken' => $resetToken
            ]
        );
    }

    /**
     * Confirm password changing
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirmPasswordReset()
    {
        return view('main.reset-password-complete');
    }

    /**
     * FAQ page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function faq()
    {
        return view(
            'main.faq',
            [
                'menuPrefix' => '/',
            ]
        );
    }

    /**
     * Bounty page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bounty()
    {
        return view(
            'main.bounty',
            [
                'menuPrefix' => '/',
            ]
        );
    }

    /**
     * Twitter Bounty Campaign
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function twitterBountyCampaign()
    {
        return view(
            'main.twitter-campaign',
            [
                'menuPrefix' => '/',
            ]
        );
    }

    /**
     * Facebook Bounty Campaign
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function facebookBountyCampaign()
    {
        return view(
            'main.facebook-campaign',
            [
                'menuPrefix' => '/',
            ]
        );
    }

    /**
     * Youtube Bounty Campaign
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function youtubeBountyCampaign()
    {
        return view(
            'main.youtube-campaign',
            [
                'menuPrefix' => '/',
            ]
        );
    }

    /**
     * Blog Bounty Campaign
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogsBountyCampaign()
    {
        return view(
            'main.blogs-article-campaign',
            [
                'menuPrefix' => '/',
            ]
        );
    }

    /**
     * Support Bounty Campaign
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function supportBountyCampaign()
    {
        return view(
            'main.support-campaign',
            [
                'menuPrefix' => '/',
            ]
        );
    }

    /**
     * Telegram Bounty Campaign
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function telegramBountyCampaign()
    {
        return view(
            'main.telegram-campaign',
            [
                'menuPrefix' => '/',
            ]
        );
    }

    /**
     * Mobile App page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mobileApp()
    {
        return view(
            'main.mobile-app',
            [
                'menuPrefix' => '/',
            ]
        );
    }

    /**
     * Affiliate page
     *
     * @return View
     */
    public function affiliate()
    {
        return view(
            'main.affiliate',
            [
                'menuPrefix' => '/',
            ]
        );
    }

    /**
     * Send contact us email
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactUs(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string|max:255|bail',
                'email' => 'required|email|max:255|bail',
                'message' => 'required|bail'
            ],
            ValidationMessages::getList(
                [
                    'name' => 'Name',
                    'email' => 'Email',
                    'message' => 'Message',
                ]
            )

        );

        try {

	        CaptchaService::checkCaptcha($request->captcha);

            MailService::sendContactUsEmail($request->email, $request->name, $request->message);

        } catch (\Exception $e) {

	        $message = 'Can not send a message';
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

        return response()->json(
            []
        );
    }

    /**
     * Send question email
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function question(Request $request)
    {
        $this->validate(
            $request,
            [
                'subject' => 'required|string|max:50|bail',
                'name' => 'required|string|max:255|bail',
                'email' => 'required|string|email|max:255|bail',
                'question' => 'required|bail'
            ],
            ValidationMessages::getList(
                [
                    'subject' => 'Subject',
                    'name' => 'Name',
                    'email' => 'Email',
                    'question' => 'Question',
                ]
            )
        );

        try {

	        CaptchaService::checkCaptcha($request->captcha);

            MailService::sendQuestionEmail($request->email, $request->name, $request->question, $request->subject);

        } catch (\Exception $e) {

	        $message = 'Can not send a message';
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

        return response()->json(
            []
        );
    }

    /**
     * Send activation email to the user
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function activateAccount(Request $request)
    {
        $this->validate(
            $request,
            [
                'uid' => 'required|string|bail',
            ],
            ValidationMessages::getList(
                [
                    'uid' => 'User ID',
                ]
            )
        );

        try {

            $user = AccountsService::findUser(
                [
                    'uid' => $request->uid
                ]
            );

            MailService::sendActivateAccountEmail($user->email, $user->uid);

        } catch (\Exception $e) {

            return response()->json(
                []
            );

        }

        return response()->json(
            []
        );
    }

    /**
     * Confirm user activation
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmActivation(Request $request)
    {
        try {

            AccountsService::activateAccount($request->uid);

        } catch (\Exception $e) {

            return redirect('/');

        }

        return view('main.confirm-email');
    }
}
