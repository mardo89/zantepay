<?php

namespace App\Http\Controllers;

use App\Models\DebitCard;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Users list
     *
     * @return View
     */
    public function users()
    {
        $usersList = [];

        foreach (User::all() as $user) {
            $referrer = User::find($user->referrer);

            $usersList[] = [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'avatar' => !is_null($user->avatar) ? $user->avatar : '/images/avatar.png',
                'status' => User::getStatus($user->status),
                'role' => User::getRole($user->role),
                'referrer' => $user->referrer,
                'profileLink' => action('AdminController@profile', ['uid' => $user->uid]),
                'referrerEmail' => !is_null($referrer) ? $referrer->email : '',
                'referrerLink' => !is_null($referrer) ? action('AdminController@profile', ['uid' => $referrer->uid]) : '',
            ];
        }

        $rolesList = User::getRolesList();

        $statusesList = [
            [
                'id' => User::USER_STATUS_INACTIVE,
                'name' => User::getStatus(User::USER_STATUS_INACTIVE)
            ],
            [
                'id' => User::USER_STATUS_NOT_VERIFIED,
                'name' => User::getStatus(User::USER_STATUS_NOT_VERIFIED)
            ],
            [
                'id' => User::USER_STATUS_IDENTITY_VERIFIED,
                'name' => User::getStatus(User::USER_STATUS_IDENTITY_VERIFIED)
            ],
            [
                'id' => User::USER_STATUS_ADDRESS_VERIFIED,
                'name' => User::getStatus(User::USER_STATUS_ADDRESS_VERIFIED)
            ],
            [
                'id' => User::USER_STATUS_VERIFIED,
                'name' => User::getStatus(User::USER_STATUS_VERIFIED)
            ],
        ];

        return view(
            'admin.users',
            [
                'users' => $usersList,
                'roles' => $rolesList,
                'statuses' => $statusesList
            ]
        );
    }


    /**
     * User profile
     *
     * @param Request $request
     *
     * @return View
     */
    public function profile(Request $request)
    {
        $userID = $request->uid;

        $user = User::where('uid', $userID)->first();

        if (!$user) {
            return redirect('admin/users');
        }

        // USER Profile
        $userProfile = [
            'uid' => $user->uid,
            'email' => $user->email,
            'name' => $user->first_name . ' ' . $user->last_name,
            'role' => $user->role
        ];

        // USER Referrer
        $referrer = User::find($user->referrer);

        if (!is_null($referrer)) {
            $userReferrer = [
                'email' => $referrer->email,
                'name' => $referrer->first_name . ' ' . $referrer->last_name,
                'role' => User::getRole($referrer->role)
            ];
        } else {
            $userReferrer = null;
        }

        // USER Referrals
        $userReferrals = [];

        foreach ($user->referrals as $referral) {
            $userName = ($referral->first_name != '' && $referral->last_name != '')
                ? $referral->first_name . ' ' . $referral->last_name
                : $referral->email;

            $userReferrals[] = [
                'name' => $userName,
                'status' => '',
            ];
        }

        // Debit Card
        $debitCard = DebitCard::where('user_id', $user->id)->first();

        if (!is_null($debitCard)) {
            $userDebitCard = $debitCard->design;
        } else {
            $userDebitCard = null;
        }


        // Documents
        $userIDDocuments = [];
        $userAddressDocuments = [];

        $documents = Document::where('user_id', $user->id)->get();

        foreach ($documents as $document) {
            if ($document->document_type === Document::DOCUMENT_TYPE_IDENTITY) {
                $userIDDocuments[] = action('AdminController@document', ['did' => $document->did]);
            } else {
                $userAddressDocuments[] = action('AdminController@document', ['did' => $document->did]);
            }
        }

        // Roles list
        $rolesList = User::getRolesList();

        return view(
            'admin.profile',
            [
                'profile' => $userProfile,
                'referrer' => $userReferrer,
                'referrals' => $userReferrals,
                'debitCard' => $userDebitCard,
                'idDocuments' => $userIDDocuments,
                'addressDocuments' => $userAddressDocuments,
                'userRoles' => $rolesList
            ]
        );
    }

    /**
     * Save user profile
     *
     * @param Request $request
     *
     * @return View
     */
    public function saveProfile(Request $request)
    {
        $this->validate($request, [
            'uid' => 'required|string',
            'role' => 'required|integer',
        ]);

        $user = User::where('uid', $request->uid)->first();

        if (is_null($user)) {
            return response()->json(
                [
                    'message' => 'User does not exist',
                    'errors' => ['Error saving user role']
                ],
                422
            );
        }

        $user->role = $request->role;
        $user->save();

        return response()->json(
            []
        );
    }

    /**
     * Save user profile
     *
     * @param Request $request
     *
     * @return View
     */
    public function approveDocument(Request $request)
    {
        $this->validate($request, [
            'uid' => 'required|string',
            'type' => 'required|integer',
        ]);

        $user = User::where('uid', $request->uid)->first();

        if (is_null($user)) {
            return response()->json(
                [
                    'message' => 'User does not exist',
                    'errors' => ['Error approving documents.']
                ],
                422
            );
        }

        if ($request->type == Document::DOCUMENT_TYPE_IDENTITY) {
            if ($user->status == User::USER_STATUS_NOT_VERIFIED) {
                $user->status = User::USER_STATUS_IDENTITY_VERIFIED;
            }

            if ($user->status == User::USER_STATUS_ADDRESS_VERIFIED) {
                $user->status = User::USER_STATUS_VERIFIED;
            }
        } else {
            if ($user->status == User::USER_STATUS_NOT_VERIFIED) {
                $user->status = User::USER_STATUS_ADDRESS_VERIFIED;
            }

            if ($user->status == User::USER_STATUS_IDENTITY_VERIFIED) {
                $user->status = User::USER_STATUS_VERIFIED;
            }
        }

        $user->save();

        return response()->json(
            [
                'status' => $user->status
            ]
        );
    }

    /**
     * Show user document
     *
     * @param Request $request
     *
     * @return View
     */
    public function document(Request $request)
    {
        $this->validate($request, [
            'did' => 'required|string',
        ]);

        $document = Document::where('did', $request->did)->first();

        if (!$document) {
            return redirect('admin/users');
        }

        return response()->download(storage_path('app/' . $document->file_path));
    }

}
