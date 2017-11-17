<?php

namespace App\Http\Controllers;

use App\Mail\ContactUs;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\ActivateAccount;
use Illuminate\Support\Facades\Mail;


class MailController extends Controller
{

    /**
     * Send activation email to the user
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function activateAccount(Request $request)
    {
        $userID = $request->input('uid', '');

        $user = User::where('uid', $userID)->first();;

        if ($user) {

            $activationLink = action('AuthController@activate', ['uid' => $user->uid]);

            Mail::to($user->email)->send(new ActivateAccount($activationLink));
        }

        return response()->json([]);
    }

    /**
     * Send contact us email
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function contactUs(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'message' => 'required|string'
        ]);

        Mail::to(env('CONTACT_EMAIL'))->send(new ContactUs($request->name, $request->email, $request->message));

        return response()->json([]);
    }

}
