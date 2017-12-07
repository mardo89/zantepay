<?php

namespace App\Http\Controllers;

use App\Models\IcoRegistration;
use App\Models\State;
use App\Models\StaticText;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
                    'btc' => IcoRegistration::CURRENCY_TYPE_BTC,
                    'eth' => IcoRegistration::CURRENCY_TYPE_ETH,
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

        $states = State::where('country_id', $request->country)->orderBy('name', 'asc')->get();

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

            $registration = IcoRegistration::create(
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

        return response()->json(
            [
                'email' => $registration['email']
            ]
        );
    }

    /**
     * Confirm user activation
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmation(Request $request)
    {
        $userID = $request->input('uid', '');

        $user = User::where('uid', $userID)->first();;

        if ($user) {
            $user->status = User::USER_STATUS_NOT_VERIFIED;
            $user->save();
        }

        return view('main.confirm-email');
    }

    /**
     * Confirm user invitation
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invitation(Request $request)
    {
        $this->checkReferrer($request->ref);

        return view('main.confirm-invitation');
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
