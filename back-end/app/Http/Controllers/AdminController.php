<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Currency;
use App\Models\DebitCard;
use App\Models\Document;
use App\Models\Invite;
use App\Models\Profile;
use App\Models\State;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Verification;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;
use Psy\Util\Json;


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

            // Referrals
            $isReferrer = User::where('referrer', $user->id)->count() != 0;

            $usersList[] = [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'avatar' => !is_null($user->avatar) ? $user->avatar : '/images/avatar.png',
                'status' => User::getStatus($user->status),
                'role' => User::getRole($user->role),
                'isReferrer' => $isReferrer,
                'profileLink' => action('AdminController@profile', ['uid' => $user->uid]),
            ];
        }

        $rolesList = User::getRolesList();

        $statusesList = [
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
            [
                'id' => User::USER_STATUS_INACTIVE,
                'name' => User::getStatus(User::USER_STATUS_INACTIVE)
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

        // Profile
        $profile = $user->profile;

        $profile->passportExpDate = is_null($profile->passport_expiration_date)
            ? ''
            : date('m/d/Y', strtotime($profile->passport_expiration_date));

        $profile->birthDate = is_null($profile->birth_date)
            ? ''
            : date('m/d/Y', strtotime($profile->birth_date));

        $country = Country::find($profile->country_id);
        $profile->countryName = !is_null($country) ? $country->name : '';

        $state = State::find($profile->state_id);
        $profile->stateName = !is_null($state) ? $state->name : '';

        // Verification
        $verification = $user->verification;

        $verification->idStatusName = $verification->id_documents_status != Verification::DOCUMENTS_DECLINED
            ? Verification::getStatus($verification->id_documents_status)
            : Verification::getStatus($verification->id_documents_status) . ' - ' . $verification->id_decline_reason;

        $verification->addressStatusName = $verification->address_documents_status != Verification::DOCUMENTS_DECLINED
            ? Verification::getStatus($verification->address_documents_status)
            : Verification::getStatus($verification->address_documents_status) . ' - ' . $verification->address_decline_reason;

        // Documents
        $userIDDocuments = [];
        $userAddressDocuments = [];

        $documents = Document::where('user_id', $user->id)->get();

        foreach ($documents as $document) {
            if ($document->document_type === Document::DOCUMENT_TYPE_IDENTITY) {
                $userIDDocuments[] = [
                    'src' => action('AdminController@document', ['did' => $document->did]),
                    'type' => Storage::mimeType($document->file_path)
                ];
            } else {
                $userAddressDocuments[] = [
                    'src' => action('AdminController@document', ['did' => $document->did]),
                    'type' => Storage::mimeType($document->file_path)
                ];
            }
        }

        // USER Referrer
        $referrerEmail = '';

        if (!is_null($user->referrer)) {
            $referrer = User::find($user->referrer);

            $referrerEmail = is_null($referrer) ? 'User deleted' : $referrer->email;
        }

        // Debit Card
        $debitCard = DebitCard::where('user_id', $user->id)->first();

        if (!is_null($debitCard)) {
            $userDebitCard = $debitCard->design;
        } else {
            $userDebitCard = null;
        }

        // Wallet
        $wallet = $user->wallet;

        // Roles list
        $rolesList = ($user->id == Auth::user()->id) ? [] : User::getRolesList();

        return view(
            'admin.profile',
            [
                'user' => $user,
                'profile' => $profile,
                'verification' => $verification,
                'idDocuments' => $userIDDocuments,
                'addressDocuments' => $userAddressDocuments,
                'referrer' => $referrerEmail,
                'debitCard' => $userDebitCard,
                'wallet' => $wallet,
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
     * Remove user with all data
     *
     * @param Request $request
     *
     * @return View
     */
    public function removeProfile(Request $request)
    {
        $this->validate($request, [
            'uid' => 'required|string',
        ]);

        $user = User::where('uid', $request->uid)->first();

        if (is_null($user)) {
            return response()->json(
                [
                    'message' => 'User does not exist',
                    'errors' => ['Error deleting user']
                ],
                422
            );
        }

        DB::beginTransaction();

        try {

            Profile::where('user_id', $user->id)->delete();
            Invite::where('user_id', $user->id)->delete();
            DebitCard::where('user_id', $user->id)->delete();
            Verification::where('user_id', $user->id)->delete();

            // Documents
            $documents = Document::where('user_id', $user->id)->get();
            foreach ($documents as $document) {
                if (Storage::exists($document->file_path)) {
                    Storage::delete($document->file_path);
                }
            }
            Document::where('user_id', $user->id)->delete();

            // Wallet
            $wallet = $user->wallet;
            Transaction::where('wallet_id', $wallet->id)->delete();
            Wallet::where('user_id', $user->id)->delete();

            $user->delete();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error deleting user',
                    'errors' => ['An error occurred']
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            []
        );
    }


    /**
     * Approve document
     *
     * @param Request $request
     *
     * @return Json
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

        DB::beginTransaction();

        try {
            $verification = $user->verification;

            if ($request->type == Document::DOCUMENT_TYPE_IDENTITY) {
                $verification->id_documents_status = Verification::DOCUMENTS_APPROVED;
                $verification->id_decline_reason = '';

                $user->status = User::USER_STATUS_IDENTITY_VERIFIED;
            } else {
                $verification->address_documents_status = Verification::DOCUMENTS_APPROVED;
                $verification->address_decline_reason = '';

                $user->status = User::USER_STATUS_ADDRESS_VERIFIED;
            }

            $verification->save();

            $verificationComplete = $verification->id_documents_status == Verification::DOCUMENTS_APPROVED
                && $verification->address_documents_status == Verification::DOCUMENTS_APPROVED;

            if ($verificationComplete) {
                $user->status = User::USER_STATUS_VERIFIED;
            }

            $user->save();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error approving documents',
                    'errors' => ['Error approving documents']
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            [
                'status' => $user->status
            ]
        );
    }


    /**
     * Decline document
     *
     * @param Request $request
     *
     * @return Json
     */
    public function declineDocument(Request $request)
    {
        $this->validate($request, [
            'uid' => 'required|string',
            'type' => 'required|integer',
            'reason' => 'string|nullable',
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

        DB::beginTransaction();

        try {
            $verification = $user->verification;

            if ($request->type == Document::DOCUMENT_TYPE_IDENTITY) {
                $verification->id_documents_status = Verification::DOCUMENTS_DECLINED;
                $verification->id_decline_reason = $request->reason;

                $user->status = User::USER_STATUS_ADDRESS_VERIFIED;
            } else {
                $verification->address_documents_status = Verification::DOCUMENTS_DECLINED;
                $verification->address_decline_reason = $request->reason;

                $user->status = User::USER_STATUS_IDENTITY_VERIFIED;
            }

            $verification->save();

            $verificationComplete = $verification->id_documents_status != Verification::DOCUMENTS_APPROVED
                && $verification->address_documents_status != Verification::DOCUMENTS_APPROVED;

            if ($verificationComplete) {
                $user->status = User::USER_STATUS_NOT_VERIFIED;
            }

            $user->save();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error approving documents',
                    'errors' => ['Error approving documents']
                ],
                422
            );

        }

        DB::commit();

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

        $mimeType = Storage::mimeType($document->file_path);

        return response()->file(
            storage_path('app/' . $document->file_path),
            ['Content-Type' => $mimeType]
        );
    }


    /**
     * Update ZNX amount
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function addZNX(Request $request)
    {
        $this->validate(
            $request,
            [
                'uid' => 'required|string',
                'amount' => 'required|numeric'
            ]
        );

        $userID = $request->uid;

        $user = User::where('uid', $userID)->first();

        if (!$user) {
            return redirect('admin/wallets');
        }

        DB::beginTransaction();

        try {
            $wallet = $user->wallet;
            $wallet->znx_amount += $request->amount;
            $wallet->save();

            Transaction::create(
                [
                    'wallet_id' => $wallet->id,
                    'currency' => Currency::CURRENCY_TYPE_ZNX,
                    'amount' => $request->amount,
                    'user_id' => Auth::user()->id
                ]
            );
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => $e->getMessage(),//'Error updating ZNX amount',
                    'errors' => ['An error occurred']
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            [
                'totalAmount' => $wallet->znx_amount
            ]
        );
    }

    /**
     * Update wallet link
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function updateWallet(Request $request)
    {
        $this->validate(
            $request,
            [
                'uid' => 'required|string',
                'currency' => 'required|numeric',
                'address' => 'string|nullable',
            ]
        );

        $userID = $request->uid;

        $user = User::where('uid', $userID)->first();

        if (!$user) {
            return redirect('admin/users');
        }

        DB::beginTransaction();

        try {
            $wallet = $user->wallet;

            switch ($request->currency) {
                case Currency::CURRENCY_TYPE_BTC:
                    $wallet->btc_wallet = $request->address;
                    break;

                case Currency::CURRENCY_TYPE_ETH:
                    $wallet->eth_wallet = $request->address;
                    break;

                case Currency::CURRENCY_TYPE_ZNX:
                    $wallet->znx_wallet = $request->address;
                    break;
            }

            $wallet->save();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => $e->getMessage(),//'Error updating ZNX amount',
                    'errors' => ['An error occurred']
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            []
        );
    }

}
