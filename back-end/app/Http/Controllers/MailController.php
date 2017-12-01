<?php

namespace App\Http\Controllers;

use App\Mail\ContactUs;
use App\Mail\IcoRegistration;
use App\Mail\InviteFriend;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\ActivateAccount;
use Illuminate\Support\Facades\Auth;
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
    public function activateAccount(Request $request)
    {
        $userID = $request->input('uid', '');

        $user = User::where('uid', $userID)->first();;

        if ($user) {

            Mail::to($user->email)->send(new ActivateAccount($user->uid));
        }

        return response()->json(
            []
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
            ]
        );

        Mail::to(env('CONTACT_EMAIL'))->send(new ContactUs($request->name, $request->email, $request->message));

        return response()->json(
            []
        );
    }

    /**
     * Send invitation
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function inviteFriend(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|string|email|max:255'
            ]
        );

        $user = Auth::user();

        if ($user) {
            Mail::to($request->email)->send(new InviteFriend($user->email, $user->uid));
        }

        return response()->json(
            []
        );
    }

    /**
     * Send invitation
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function icoRegistration(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|string|email|max:255',
            ]
        );

        $email = $request->email;
        $link = action('IndexController@main');

        Mail::to($email)->send(new IcoRegistration($link));

        return response()->json(
            []
        );
    }

}
