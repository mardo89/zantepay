<?php

namespace App\Http\Controllers;

use App\Mail\ApproveDocuments;
use App\Models\DB\Wallet;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Services\BonusesService;
use App\Models\Wallet\Currency;
use App\Models\DB\Country;
use App\Models\DB\DebitCard;
use App\Models\DB\Document;
use App\Models\DB\State;
use App\Models\DB\User;
use App\Models\DB\Verification;
use App\Models\Validation\ValidationMessages;
use App\Models\Wallet\Ico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
        $rolesList = User::getRolesList();

        $statusesList = [
            [
                'id' => User::USER_STATUS_PENDING,
                'name' => User::getStatus(User::USER_STATUS_PENDING)
            ],
            [
                'id' => User::USER_STATUS_NOT_VERIFIED,
                'name' => User::getStatus(User::USER_STATUS_NOT_VERIFIED)
            ],
            [
                'id' => User::USER_STATUS_VERIFICATION_PENDING,
                'name' => User::getStatus(User::USER_STATUS_VERIFICATION_PENDING)
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
            [
                'id' => User::USER_STATUS_WITHDRAW_PENDING,
                'name' => User::getStatus(User::USER_STATUS_WITHDRAW_PENDING)
            ]
        ];

        return view(
            $this->getViewPrefix() . 'users',
            [
                'roles' => $rolesList,
                'statuses' => $statusesList
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
                    'page' => 'Page',
                    'sort_index' => 'Sort Column',
                    'sort_order' => 'Sort Order',
                ]
            )
        );

        $roleFilter = $request->input('role_filter', []);
        $statusFilter = $request->input('status_filter', []);
        $referrerFilter = $request->input('referrer_filter', []);
        $nameFilter = $request->input('name_filter', '');
        $page = $request->input('page', 1);
        $sortIndex = $request->input('sort_index', 0);
        $sortOrder = $request->input('sort_order', 0);

        $queryBuilder = User::with('referrals');

        if (count($roleFilter) > 0) {
            $queryBuilder->whereIn('role', $roleFilter);
        }

        if (count($statusFilter) > 0) {
            $queryBuilder->whereIn('status', $statusFilter);
        }

        if ($nameFilter) {
            $queryBuilder->where(
                function ($query) use ($nameFilter) {
                    $query->where('first_name', 'like', '%' . $nameFilter . '%')
                        ->orWhere('last_name', 'like', '%' . $nameFilter . '%')
                        ->orWhere('email', 'like', '%' . $nameFilter . '%');
                }
            );
        }

        // sort
        switch ($sortIndex) {
            case 0:
                $sortColumn = 'email';
                break;

            case 1:
                $sortColumn = 'first_name';
                break;

            default:
                $sortColumn = 'id';
        }

        $users = $queryBuilder->orderBy($sortColumn, $sortOrder)->get();

        // Users List
        $usersList = [];

        foreach ($users as $user) {
            $hasReferrals = $user->referrals->count() > 0 ? 1 : 0;

            if (count($referrerFilter) > 0 && !in_array($hasReferrals, $referrerFilter)) {
                continue;
            }

            $usersList[] = [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'avatar' => !is_null($user->avatar) ? $user->avatar : '/images/avatar.png',
                'status' => User::getStatus($user->status),
                'role' => User::getRole($user->role),
                'hasReferrals' => $user->referrals->count() > 0 ? 'YES' : 'NO',
                'profileLink' => action('ManagerController@profile', ['uid' => $user->uid]),
            ];
        }

        // Paginator
        $rowsPerPage = 25;

        $totalPages = ceil(count($usersList) / $rowsPerPage);
        $usersList = array_slice($usersList, ($page - 1) * $rowsPerPage, $rowsPerPage);

        return response()->json(
            [
                'usersList' => $usersList,
                'paginator' => [
                    'currentPage' => $page,
                    'totalPages' => $totalPages
                ]
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
                    'src' => action('ManagerController@document', ['did' => $document->did]),
                    'type' => Storage::mimeType($document->file_path)
                ];
            } else {
                $userAddressDocuments[] = [
                    'src' => action('ManagerController@document', ['did' => $document->did]),
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
        $rolesList = User::getRolesList();

        return view(
            $this->getViewPrefix() . 'profile',
            [
                'user' => $user,
                'profile' => $profile,
                'verification' => $verification,
                'idDocuments' => $userIDDocuments,
                'addressDocuments' => $userAddressDocuments,
                'referrer' => $referrerEmail,
                'debitCard' => $userDebitCard,
                'wallet' => $wallet,
                'userRoles' => $rolesList,
                'canEdit' => Auth::user()->uid != $userID
            ]
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

                $user->changeStatus(User::USER_STATUS_IDENTITY_VERIFIED);

            } else {

                $verification->address_documents_status = Verification::DOCUMENTS_APPROVED;
                $verification->address_decline_reason = '';

                $verificationStatus = Verification::getStatus($verification->address_documents_status);

                $user->changeStatus(User::USER_STATUS_ADDRESS_VERIFIED);

            }

            $verification->save();

            $verificationComplete = $verification->id_documents_status == Verification::DOCUMENTS_APPROVED
                && $verification->address_documents_status == Verification::DOCUMENTS_APPROVED;

            if ($verificationComplete) {
                $user->changeStatus(User::USER_STATUS_VERIFIED);

                BonusesService::updateBonus($user);

                Mail::to($user->email)->send(new ApproveDocuments());
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
                $user->changeStatus(User::USER_STATUS_IDENTITY_VERIFIED);
            } elseif ($verification->address_documents_status == Verification::DOCUMENTS_APPROVED) {
                $user->changeStatus(User::USER_STATUS_ADDRESS_VERIFIED);
            } else {
                $user->changeStatus(User::USER_STATUS_NOT_VERIFIED);
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
