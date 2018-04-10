<?php

namespace App\Http\Controllers;

use App\Mail\InviteFriend;
use App\Mail\Question;
use App\Models\DB\User;
use App\Models\Validation\ValidationMessages;
use App\Mail\ActivateAccount;
use App\Mail\ContactUs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;


class MailController extends Controller
{

    /**
     * Send invitation
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function inviteFriend(Request $request)
    {
        $user = Auth::user();

        $this->validate(
            $request,
            [
                'email' => 'required|string|email|max:255'
            ]
        );

        try {

            Mail::to($request->email)->send(new InviteFriend($user->uid));

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Invitation failed',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            []
        );
    }
}
