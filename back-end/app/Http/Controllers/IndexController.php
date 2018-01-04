<?php

namespace App\Http\Controllers;

use App\Mail\IcoRegistrationAdmin as IcoRegistrationAdminMail;
use App\Mail\IcoRegistration as IcoRegistrationMail;
use App\Models\Currency;
use App\Models\DB\IcoRegistration;
use App\Models\DB\Investor;
use App\Models\DB\PasswordReset;
use App\Models\DB\State;
use App\Models\DB\User;
use App\Models\Validation\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;


class IndexController extends Controller
{
    /**
     * Main page
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function main(Request $request)
    {
        $this->checkReferrer($request->ref);

        return view(
            'main.index',
            [
                'currency' => [
                    'btc' => Currency::CURRENCY_TYPE_BTC,
                    'eth' => Currency::CURRENCY_TYPE_ETH,
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

            $link = action('IndexController@main');

            Mail::to($email)->send(new IcoRegistrationMail($link));
            Mail::send(new IcoRegistrationAdminMail($email, $currency, $amount));

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
                    'first-name' => 'First NAme',
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

        $user->status = User::USER_STATUS_NOT_VERIFIED;
        $user->save();

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
}
