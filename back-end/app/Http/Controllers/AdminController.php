<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\DebitCard;
use App\Models\Document;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;


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
                'status' => User::getStatus($referral->status),
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
                'idDocumentsApproved' => $user->status === User::USER_STATUS_IDENTITY_VERIFIED || $user->status === User::USER_STATUS_VERIFIED,
                'addressDocuments' => $userAddressDocuments,
                'addressDocumentsApproved' => $user->status === User::USER_STATUS_ADDRESS_VERIFIED || $user->status === User::USER_STATUS_VERIFIED,
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

        $mimeType = Storage::mimeType($document->file_path);

        return response()->file(
            storage_path('app/' . $document->file_path),
            ['Content-Type' => $mimeType]
        );
    }

    /**
     * Wallets list
     *
     * @return View
     */
    public function wallets()
    {
        $usersList = [];

        $users = User::where('role', User::USER_ROLE_USER)->get();

        foreach ($users as $user) {
            $debitCard = DebitCard::where('user_id', $user->id)->first();

            $wallet = $user->wallet;

            $dcDesign = DebitCard::getDesign(DebitCard::DESIGN_NOT_SELECTED);

            if (!is_null($debitCard)) {
               $dcDesign = DebitCard::getDesign($debitCard->design);
            }

            $usersList[] = [
                'id' => $user->id,
                'email' => $user->email,
                'debitCard' => $dcDesign,
                'ztx' => $wallet->znx_amount,
                'walletLink' => action('AdminController@wallet', ['uid' => $user->uid]),
            ];
        }

        $dcList = DebitCard::getCardsList();

        return view(
            'admin.wallets',
            [
                'users' => $usersList,
                'debitCards' => $dcList
            ]
        );
    }

    /**
     * User wallet
     *
     * @param Request $request
     *
     * @return View
     */
    public function wallet(Request $request)
    {
        $userID = $request->uid;

        $user = User::where('uid', $userID)->first();

        if (!$user) {
            return redirect('admin/wallets');
        }

        // USER Profile
        $userProfile = [
            'uid' => $user->uid,
            'email' => $user->email,
            'name' => $user->first_name . ' ' . $user->last_name,
            'status' => User::getStatus($user->status)
        ];

        // Debit Card
        $debitCard = DebitCard::where('user_id', $user->id)->first();

        $dcDesign = DebitCard::DESIGN_NOT_SELECTED;

        if (!is_null($debitCard)) {
            $dcDesign = $debitCard->design;
        }

        $wallet = $user->wallet;

        $walletTransactions = Transaction::where('wallet_id', $wallet->id)->orderBy('created_on', 'desc')->get();

        $transactions = [];

        foreach ($walletTransactions as $walletTransaction) {
            $manager = User::find($walletTransaction->user_id);

            $transactions[] = [
                'date' => date('m-d-Y H:i:s', strtotime($walletTransaction->created_on)),
                'currency' => Currency::getCurrency($walletTransaction->currency),
                'amount' => $walletTransaction->amount,
                'manager' => $manager->email
            ];
        }

        return view(
            'admin.wallet',
            [
                'profile' => $userProfile,
                'debitCard' => $dcDesign,
                'transactions' => $transactions
            ]
        );
    }

    /**
     * Update ZNX amount
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function updateZNXWallet(Request $request)
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

            $transactionInfo = Transaction::create(
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

        $walletTransaction = Transaction::find($transactionInfo['id']);

        return response()->json(
            [
                'date' => date('m-d-Y H:i:s', strtotime($walletTransaction->created_on)),
                'currency' => Currency::getCurrency($walletTransaction->currency),
                'amount' => $walletTransaction->amount,
                'manager' => Auth::user()->email
            ]
        );
    }

}
