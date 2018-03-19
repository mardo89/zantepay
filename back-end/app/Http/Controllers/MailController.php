<?php

namespace App\Http\Controllers;

use App\Mail\InviteFriend;
use App\Mail\Question;
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
                [
                    'message' => 'Can not send a message',
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
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'question' => 'required'
            ],
            ValidationMessages::getList(
                [
                    'name' => 'Name',
                    'email' => 'Email',
                    'question' => 'Question',
                ]
            )

        );

        $name = $request->input('name');
        $email = $request->input('email');
        $question = $request->input('question');

        try {

            Mail::to(env('CONTACT_EMAIL'))->send(new Question($name, $email, $question));

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
