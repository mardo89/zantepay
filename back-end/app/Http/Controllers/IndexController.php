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
    public function main(Request $request) {
        if (!is_null($request->ref)) {
            $user = User::where('uid', $request->ref)->first();

            if (!is_null($user)) {
                Session::put('referrer', $user->id);
            }
        }

        return view('index');
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
                    'errorMessage' => 'Registration failed',
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

}
