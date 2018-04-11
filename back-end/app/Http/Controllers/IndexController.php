<?php

namespace App\Http\Controllers;

use App\Models\DB\ExternalRedirect;
use App\Models\Services\MailService;
use App\Models\Services\UsersService;
use App\Models\Wallet\Currency;
use App\Models\DB\IcoRegistration;
use App\Models\DB\Investor;
use App\Models\DB\PasswordReset;
use App\Models\DB\User;
use App\Models\Validation\ValidationMessages;
use App\Models\Wallet\CurrencyFormatter;
use App\Models\Wallet\Ico;
use App\Models\Wallet\RateCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


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
        $this->checkReferrer($request->ref);

        $this->checkExternals();

        $ico = new Ico();

        $activePart = $ico->getActivePart();

        $icoPartName = optional($activePart)->getName() ?? '';
        $icoPartEndDate = optional($activePart)->getEndDate() ?? '';
        $icoPartLimit = optional($activePart)->getLimit() ?? '';

        $icoPartEthRate = optional($activePart)->getEthRate() ?? 0;
        $icoPartEuroRate = optional($activePart)->getEuroRate() ?? 0;
        $icoPartZnxRate = RateCalculator::toZnx(1, $icoPartEthRate);

        $icoPartAmount = optional($activePart)->getAmount() ?? 0;
        $icoPartRelativeBalance = optional($activePart)->getRelativeBalance() ?? 0;

        $ethLimit = RateCalculator::fromZnx($icoPartLimit, $icoPartEthRate);
        $ethAmount = RateCalculator::fromZnx($icoPartAmount, $icoPartEthRate);

        return view(
            'main.index',
            [
                'menuPrefix' => '',
                'currency' => [
                    'btc' => Currency::CURRENCY_TYPE_BTC,
                    'eth' => Currency::CURRENCY_TYPE_ETH,
                ],
                'ico' => [
                    'name' => $icoPartName,
                    'endDate' => date('Y/m/d H:i:s', strtotime($icoPartEndDate)),
                    'znxLimit' => number_format($icoPartLimit, 0, ',', '.'),
                    'znxAmount' => number_format($icoPartAmount, 0, ',', '.'),
                    'ethLimit' => number_format($ethLimit, 0, ',', '.'),
                    'ethAmount' => number_format($ethAmount, 0, ',', '.'),
                    'znxRate' => (new CurrencyFormatter($icoPartZnxRate))->znxFormat()->get(),
                    'ethRate' => (new CurrencyFormatter($icoPartEthRate))->ethFormat()->get(),
                    'euroRate' => (new CurrencyFormatter($icoPartEuroRate))->ethFormat()->get(),
                    'relativeBalance' => [
                        'value' => $icoPartRelativeBalance,
                        'percent' => number_format($icoPartRelativeBalance * 100, 2),
                        'progressClass' => $icoPartRelativeBalance > 50 ? 'is-left' : 'is-right'
                    ]
                ]
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
                'email' => 'required|string|email|max:255',
                'amount' => 'nullable|numeric'
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                    'amount' => 'Amount'
                ]
            )
        );

        $email = $request->input('email');
        $amount = $request->input('amount', 0);
        $currencyType = Currency::CURRENCY_TYPE_ETH;
        $currency = Currency::getCurrency($currencyType);

        DB::beginTransaction();

        try {

            IcoRegistration::create(
                [
                    'email' => $email,
                    'currency' => $currencyType,
                    'amount' => $amount,
                ]
            );

            ExternalRedirect::addLink(
                Session::get('externalLink'),
                $email,
                ExternalRedirect::ACTION_TYPE_REGISTRATION_ICO
            );

            MailService::sendIcoRegistrationEmail($email);
            MailService::sendIcoRegistrationAdminEmail($email, $currency, $amount);

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
                'email' => 'required|string|email|max:255|unique:investors',
                'skype-id' => 'required|string|max:100|unique:investors,skype_id',
                'first-name' => 'required|string|max:100',
                'last-name' => 'required|string|max:100',
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

        $email = $request->input('email');
        $skype = $request->input('skype-id');
        $firstName = $request->input('first-name');
        $lastName = $request->input('last-name');

        DB::beginTransaction();

        try {

            Investor::create(
                [
                    'email' => $email,
                    'skype_id' => $skype,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]
            );

            ExternalRedirect::addLink(
                Session::get('externalLink'),
                $email,
                ExternalRedirect::ACTION_TYPE_REGISTRATION_INVESTOR
            );

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
     * Confirm user activation
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmActivation(Request $request)
    {
        $userID = $request->input('uid', '');

        $user = User::where('uid', $userID)->first();;

        if (!$user || $user->status != User::USER_STATUS_INACTIVE) {
            return redirect('/');
        }

        $user->changeStatus(User::USER_STATUS_PENDING);

        return view('main.confirm-email');
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
        $this->checkReferrer($request->ref);

        return view(
            'main.confirm-invitation',
            [
                'referralToken' => $request->ref,
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
        $resetToken = $request->input('rt', '');

        $resetInfo = PasswordReset::where('token', $resetToken)
            ->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 15 MINUTE)'))
            ->first();

        // Check for expiration date
        if (is_null($resetInfo)) {
            return view('main.reset-password-fail');
        }

        $resetEmail = $resetInfo->email;

        $lastResetInfo = PasswordReset::where('email', $resetEmail)
            ->orderBy('created_at', 'desc')
            ->first();

        // Check if there is no other tokens after current
        if (is_null($lastResetInfo) || $lastResetInfo->token !== $resetToken) {
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
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'message' => 'required'
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

            MailService::sendContactUsEmail($request->email, $request->name, $request->message);

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => $e->getMessage(),//'Can not send a message',
                    'errors' => [
                        'name' => '',
                        'message' => '',
                        'email' => 'Can not send a message'
                    ]
                ],
                422
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
                'subject' => 'required|string|max:50',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'question' => 'required'
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

            MailService::sendQuestionEmail($request->email, $request->name, $request->question, $request->subject);

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Can not send a question',
                    'errors' => [
                        'name' => '',
                        'question' => '',
                        'email' => 'Can not send a question'
                    ]
                ],
                422
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
                'uid' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'uid' => 'User ID',
                ]
            )
        );

        try {

            $user = UsersService::findUserByUid($request->uid);

            if ($user) {
                MailService::sendActivateAccountEmail($user->email, $user->uid);
            }

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
     * Check if referrer exist and store him to the Session
     *
     * @param string $referrer
     */
    protected function checkReferrer($referrer)
    {
        if (!is_null($referrer)) {
            $user = User::where('uid', $referrer)->first();

            if (!is_null($user)) {
                Session::put('referrer', $user->id);
            }
        }
    }

    /**
     * Check external redirect
     *
     */
    protected function checkExternals()
    {
        $externalLink = $_SERVER['HTTP_REFERER'] ?? '';

        Session::put('externalLink', $externalLink);
    }

}
