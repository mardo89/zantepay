<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Invite;
use App\Models\Profile;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\StaticText;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;


class UserController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * User profile
     *
     * @return View
     */
    public function profile()
    {
        $user = Auth::user();

        $profile = Profile::where('user_id', $user->id)->first();

        $userProfile = [
            'email' => $user->email,
            'first_name' => '',
            'last_name' => '',
            'phone_number' => '',
            'country_id' => 0,
            'state_id' => 0,
            'city' => '',
            'address' => '',
            'postcode' => '',
            'passport_id' => '',
            'passport_expiration_date' => date('m/d/Y'),
            'birth_date' => date('m/d/Y'),
            'birth_country' => ''
        ];

        if (!is_null($profile)) {
            $userProfile['first_name'] = $profile->first_name;
            $userProfile['last_name'] = $profile->last_name;
            $userProfile['phone_number'] = $profile->phone_number;
            $userProfile['country_id'] = $profile->country_id;
            $userProfile['state_id'] = $profile->state_id;
            $userProfile['city'] = $profile->city;
            $userProfile['address'] = $profile->address;
            $userProfile['postcode'] = $profile->postcode;
            $userProfile['passport_id'] = $profile->passport_id;
            $userProfile['passport_expiration_date'] = date('m/d/Y', strtotime($profile->passport_expiration_date));
            $userProfile['birth_date'] = date('m/d/Y', strtotime($profile->birth_date));
            $userProfile['birth_country'] = $profile->birth_country;
        }

        $countries = Country::all();
        $states = $userProfile['country_id'] === 0
            ? []
            : State::where('country_id', $userProfile['country_id'])->orderBy('name', 'asc')->get();

        return view(
            'profile',
            [
                'profile' => $userProfile,
                'countries' => $countries,
                'states' => $states
            ]
        );
    }

    /**
     * Save user profile
     *
     * @return View
     */
    public function saveProfile(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'first_name' => 'string|max:100|nullable',
            'last_name' => 'string|max:100|nullable',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id . ',id',
            'phone_number' => 'string|max:20|nullable',
            'country' => 'numeric',
            'state' => 'numeric',
            'city' => 'string|max:100|nullable',
            'address' => 'string|nullable',
            'postcode' => 'string|max:10|nullable',
            'passport' => 'string|max:50|nullable',
            'expiration_date' => 'date',
            'birth_date' => 'date',
            'birth_country' => 'string|max:50|nullable',
        ]);

        $profile = Profile::where('user_id', $user->id)->first();

        // Update email
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->save();
        }

        // Update profile
        $userProfile = [
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'country_id' => $request->country,
            'state_id' => $request->state,
            'city' => $request->city,
            'address' => $request->address,
            'postcode' => $request->postcode,
            'passport_id' => $request->passport,
            'passport_expiration_date' => date('Y-m-d H:i:s', strtotime($request->expiration_date)),
            'birth_date' => date('Y-m-d H:i:s', strtotime($request->birth_date)),
            'birth_country' => $request->birth_country
        ];

        try {
            if (!$profile) {
                Profile::create($userProfile);
            } else {
                Profile::where('user_id', $user->id)->update($userProfile);
            }

        } catch (\Exception $e) {

            return response()->json(
                [
                    'errorMessage' => $e->getMessage(),
                    'errors' => ['An error occurred']
                ],
                422
            );

        }

        return response()->json([]);
    }

    /**
     * Invite friends
     *
     * @return View
     */
    public function invite()
    {
        $user = Auth::user();

        $invites = Invite::where('user_id', $user->id)->get();

        $userReferrals = [];

        foreach ($invites as $invite) {

            $invite->statusMessage = StaticText::getInvitationStatus($invite->status);
            $invite->avatar = '/images/avatar.png';

            if ($invite->status === INVITATION_STATUS_COMPLETE) {
                $friend = User::where('email', $invite->email)->first();

                if ($friend && !is_null($friend->avatar)) {
                    $invite->avatar = '/images/' . $friend->avatar;
                }
            }

            $userReferrals[] = $invite;
        }

        return view(
            'invite-friend',
            [
                'referralLink' => action('IndexController@main', ['ref' => $user->uid]),
                'referrals' => $userReferrals
            ]
        );
    }

    /**
     * Save invitation
     *
     * @param Request $request
     *
     * @return View
     */
    public function saveInvitation(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|string|email|max:255'
            ]
        );

        $user = Auth::user();
        $email = $request->email;

        $invite = Invite::where('user_id', $user->id)->where('email', $email)->first();

        try {

            if (!$invite) {
                $invite = Invite::create(
                    [
                        'user_id' => $user->id,
                        'email' => $email
                    ]
                );
            }

        } catch (\Exception $e) {

            return response()->json(
                [
                    'errorMessage' => 'Invitation failed',
                    'errors' => ['Error sending invitation']
                ],
                422
            );

        }

        return response()->json(
            [
                'email' => $invite['email'],
                'status' => StaticText::getInvitationStatus($invite['status'])
            ]
        );
    }

}
