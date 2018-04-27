<?php

namespace App\Http\Controllers;

use App\Models\DB\ZantecoinTransaction;
use App\Models\Search\Users;
use App\Models\Services\BonusesService;
use App\Models\Services\MailService;
use App\Models\Services\ProfilesService;
use App\Models\Services\UsersService;
use App\Models\Wallet\Currency;
use App\Models\DB\Document;
use App\Models\DB\User;
use App\Models\DB\Verification;
use App\Models\Validation\ValidationMessages;
use App\Models\Wallet\Ico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ManagerController extends Controller
{
    /**
     * ManagerController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.manager');
    }

    /**
     * Users list
     *
     * @return View
     */
    public function users()
    {

        return view(
            $this->getViewPrefix() . 'users',
            [
                'roles' => UsersService::getUserRoles(),
                'statuses' => UsersService::getUserStatuses()
            ]
        );

    }

    /**
     * Search users
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function searchUsers(Request $request)
    {
        $this->validate(
            $request,
            [
                'role_filter' => 'array',
                'status_filter' => 'array',
                'referrer_filter' => 'array',
                'name_filter' => 'string|nullable',
                'date_from_filter' => 'date|nullable',
                'date_to_filter' => 'date|nullable',
                'page' => 'integer|min:1',
                'sort_index' => 'integer',
                'sort_order' => 'in:asc,desc',
            ],
            ValidationMessages::getList(
                [
                    'role_filter' => 'Role Filter',
                    'status_filter' => 'Status Filter',
                    'referrer_filter' => 'Referrer Filter',
                    'name_filter' => 'Name Filter',
                    'date_from_filter' => 'Date From Filter',
                    'date_to_filter' => 'Date To Filter',
                    'page' => 'Page',
                    'sort_index' => 'Sort Column',
                    'sort_order' => 'Sort Order',
                ]
            )
        );

        try {

            $filters = [
                'role_filter' => $request->role_filter,
                'status_filter' => $request->status_filter,
                'referrer_filter' => $request->referrer_filter,
                'name_filter' => $request->name_filter,
                'date_from_filter' => $request->date_from_filter,
                'date_to_filter' => $request->date_to_filter,
                'page' => $request->page,
            ];

            $sort = [
                'sort_index' => $request->sort_index,
                'sort_order' => $request->sort_order,
            ];

            $usersList = Users::searchUsers($filters, $sort);

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error while searching users',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            $usersList
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

        try {

            $profileInfo = ProfilesService::getInfo($request->uid);

        } catch (\Exception $e) {

            return redirect('admin/users');

        }

        return view(
            $this->getViewPrefix() . 'profile',
            $profileInfo
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
        $this->validate(
            $request,
            [
                'uid' => 'required|string',
                'type' => 'required|integer',
            ],
            ValidationMessages::getList(
                [
                    'uid' => 'User ID',
                    'type' => 'Document Type',
                ]
            )
        );

        $userID = $request->input('uid');
        $documentType = $request->input('type');

        DB::beginTransaction();

        try {

            $user = User::where('uid', $userID)->first();

            if (is_null($user)) {
                throw new \Exception('User does not exist');
            }

            $verification = $user->verification;

            if ($documentType == Document::DOCUMENT_TYPE_IDENTITY) {

                $verification->id_documents_status = Verification::DOCUMENTS_APPROVED;
                $verification->id_decline_reason = '';

                $verificationStatus = Verification::getStatus($verification->id_documents_status);

                UsersService::changeUserStatus($user, User::USER_STATUS_IDENTITY_VERIFIED);

            } else {

                $verification->address_documents_status = Verification::DOCUMENTS_APPROVED;
                $verification->address_decline_reason = '';

                $verificationStatus = Verification::getStatus($verification->address_documents_status);

                UsersService::changeUserStatus($user, User::USER_STATUS_ADDRESS_VERIFIED);

            }

            $verification->save();

            $verificationComplete = $verification->id_documents_status == Verification::DOCUMENTS_APPROVED
                && $verification->address_documents_status == Verification::DOCUMENTS_APPROVED;

            if ($verificationComplete) {
                UsersService::changeUserStatus($user, User::USER_STATUS_VERIFIED);

                BonusesService::updateBonus($user);

                MailService::sendApproveDocumentsEmail($user->email);
            }

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error approving documents',
                    'errors' => []
                ],
                500
            );

        }

        DB::commit();

        return response()->json(
            [
                'status' => $verificationStatus
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
        $this->validate(
            $request,
            [
                'uid' => 'required|string',
                'type' => 'required|integer',
                'reason' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'uid' => 'User ID',
                    'type' => 'Document Type',
                    'reason' => 'Decline Reason',
                ]
            )
        );

        $userID = $request->input('uid');
        $documentType = $request->input('type');
        $declineReason = $request->input('reason', '');

        DB::beginTransaction();

        try {
            $user = User::where('uid', $userID)->first();

            if (is_null($user)) {
                throw new \Exception('User does not exist');
            }

            $verification = $user->verification;

            if ($documentType == Document::DOCUMENT_TYPE_IDENTITY) {

                $verification->id_documents_status = Verification::DOCUMENTS_DECLINED;
                $verification->id_decline_reason = $declineReason;

                $verificationStatus = Verification::getStatus($verification->id_documents_status) . ' - ' . $verification->id_decline_reason;

            } else {

                $verification->address_documents_status = Verification::DOCUMENTS_DECLINED;
                $verification->address_decline_reason = $declineReason;

                $verificationStatus = Verification::getStatus($verification->address_documents_status) . ' - ' . $verification->address_decline_reason;
            }

            $verification->save();

            // Change user status
            if ($verification->id_documents_status == Verification::DOCUMENTS_APPROVED) {
                UsersService::changeUserStatus($user, User::USER_STATUS_IDENTITY_VERIFIED);
            } elseif ($verification->address_documents_status == Verification::DOCUMENTS_APPROVED) {
                UsersService::changeUserStatus($user, User::USER_STATUS_ADDRESS_VERIFIED);
            } else {
                UsersService::changeUserStatus($user, User::USER_STATUS_NOT_VERIFIED);
            }


        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error declining documents',
                    'errors' => []
                ],
                500
            );

        }

        DB::commit();

        return response()->json(
            [
                'status' => $verificationStatus
            ]
        );
    }

    /**
     * Show user document
     *
     * @param Request $request
     *
     * @return File
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
     * Add ZNX from ICO pool
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function addIcoZnx(Request $request)
    {
        $this->validate(
            $request,
            [
                'uid' => 'required|string',
                'amount' => 'required|numeric'
            ],
            ValidationMessages::getList(
                [
                    'amount' => 'Amount',
                ]
            )
        );

        $uid = $request->uid;
        $amount = $request->amount;

        $user = User::where('uid', $uid)->first();

        if (!$user) {
            return response()->json(
                [
                    'message' => 'User does not exist.',
                    'errors' => []
                ],
                500
            );
        }

        DB::beginTransaction();

        try {

            $ico = new Ico();

            // Create Zantecoin transaction transaction
            ZantecoinTransaction::create(
                [
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'ico_part' => $ico->getActivePart()->getID(),
                    'contribution_id' => 0,
                    'transaction_type' => ZantecoinTransaction::TRANSACTION_ADD_ICO_ZNX
                ]
            );

            $wallet = $user->wallet;
            $wallet->znx_amount += $amount;
            $wallet->save();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'There has been an error with transfer of ' . $amount . ' ZNX from ICO pool.',
                    'errors' => []
                ],
                500
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
     * Add ZNX from Foundation pool
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function addFoundationZnx(Request $request)
    {
        $this->validate(
            $request,
            [
                'uid' => 'required|string',
                'amount' => 'required|numeric'
            ],
            ValidationMessages::getList(
                [
                    'amount' => 'Amount',
                ]
            )
        );

        $uid = $request->uid;
        $amount = $request->amount;

        $user = User::where('uid', $uid)->first();

        if (!$user) {
            return response()->json(
                [
                    'message' => 'User does not exist.',
                    'errors' => []
                ],
                500
            );
        }

        DB::beginTransaction();

        try {

            $ico = new Ico();

            // Create Zantecoin transaction transaction
            ZantecoinTransaction::create(
                [
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'ico_part' => $ico->getActivePart()->getID(),
                    'contribution_id' => 0,
                    'transaction_type' => ZantecoinTransaction::TRANSACTION_ADD_FOUNDATION_ZNX
                ]
            );

            $wallet = $user->wallet;
            $wallet->znx_amount += $amount;
            $wallet->save();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'There has been an error with transfer of ' . $amount . ' ZNX from ICO pool.',
                    'errors' => []
                ],
                500
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
            ],
            ValidationMessages::getList(
                [
                    'currency' => 'Currency',
                    'address' => 'Wallet Address',
                ]
            )
        );

        DB::beginTransaction();

        try {

            $profile = optional(User::where('uid', $request->uid)->first())->profile;

            if (!$profile) {
                throw new \Exception('User does not exist');
            }

            switch ($request->currency) {
//                case Currency::CURRENCY_TYPE_BTC:
//                    $profile->btc_wallet = $request->address;
//                    break;

                case Currency::CURRENCY_TYPE_ETH:
                    $profile->eth_wallet = $request->address;
                    break;

//                case Currency::CURRENCY_TYPE_ZNX:
//                    $wallet->znx_wallet = $request->address;
//                    break;
            }

            $profile->save();

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error updating Wallet Address',
                    'errors' => []
                ],
                500
            );

        }

        DB::commit();

        return response()->json(
            []
        );
    }

    /**
     * Get prefix for View
     *
     * @return bool
     */
    protected function getViewPrefix()
    {
        return Auth::user()->role === User::USER_ROLE_ADMIN ? 'admin.' : 'manager.';
    }
}
