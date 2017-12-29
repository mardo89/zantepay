<?php

namespace App\Http\Controllers;

use App\Mail\IcoRegistrationAdmin as IcoRegistrationAdminMail;
use App\Mail\IcoRegistration as IcoRegistrationMail;
use App\Mail\ResetPassword;
use App\Models\Currency;
use App\Models\IcoRegistration;
use App\Models\PasswordReset;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
     * Get states list for country
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function getStates(Request $request)
    {
        $this->validate($request, [
            'country' => 'numeric'
        ]);

        $states = State::getStatesList();

        return response()->json($states);
    }


    /**
     * Save pre-ico registration
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
                'currency' => 'required|integer',
                'amount' => 'numeric'
            ]
        );

        try {

            IcoRegistration::create(
                [
                    'email' => $request->email,
                    'currency' => $request->currency,
                    'amount' => $request->amount,

                ]
            );

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Registration failed',
                    'errors' => ['An error occurred']
                ],
                422
            );

        }

        $email = $request->email;
        $currency = IcoRegistration::getCurrency($request->currency);
        $amount = $request->amount;
        $link = action('IndexController@main');

        Mail::to($email)->send(new IcoRegistrationMail($link));
        Mail::send(new IcoRegistrationAdminMail($email, $currency, $amount));

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

    public function confirmPasswordReset(Request $request)
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
