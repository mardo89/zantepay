<?php

namespace App\Http\Controllers;

use App\Models\DB\User;
use App\Models\Validation\ValidationMessages;
use App\Mail\ActivateAccount;
use App\Mail\ContactUs;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;


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

        try {

            $user = User::where('uid', $userID)->first();;

            if ($user) {

                Mail::to($user->email)->send(new ActivateAccount($user->uid));
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

        $name = $request->input('name');
        $email = $request->input('email');
        $message = $request->input('message');

        try {

            Mail::to(env('CONTACT_EMAIL'))->send(new ContactUs($name, $email, $message));

        } catch (\Exception $e) {

            return response()->json(
                []
            );

        }

        return response()->json(
            []
        );
    }
}
