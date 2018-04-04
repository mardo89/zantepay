<?php

namespace App\Http\Controllers;

use App\Models\Search\Transactions;
use App\Models\DB\Contribution;
use App\Models\DB\GrantCoinsTransaction;
use App\Models\DB\ZantecoinTransaction;
use App\Models\DB\DebitCard;
use App\Models\DB\Document;
use App\Models\DB\Invite;
use App\Models\DB\PasswordReset;
use App\Models\DB\Profile;
use App\Models\DB\SocialNetworkAccount;
use App\Models\DB\User;
use App\Models\DB\Verification;
use App\Models\DB\Wallet;
use App\Models\Validation\ValidationMessages;
use App\Models\Wallet\EtheriumApi;
use App\Models\Wallet\Grant;
use App\Models\Wallet\Ico;
use App\Models\Wallet\RateCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
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
        $this->validate(
            $request,
            [
                'uid' => 'required|string',
                'role' => 'required|integer',
            ],
            ValidationMessages::getList(
                [
                    'role' => 'User Role'
                ],
                [
                    'role.integer' => 'Unknown User Role',
                ]
            )
        );

        $userID = $request->uid;

        if (Auth::user()->uid === $userID) {
            return response()->json(
                [
                    'message' => 'Admin user can not update role for himself',
                    'errors' => []
                ],
                500
            );
        }

        try {

            $user = User::where('uid', $userID)->first();

            if (is_null($user)) {
                throw new \Exception('User does not exist');
            }

            $user->role = $request->role;
            $user->save();

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error changing role',
                    'errors' => []
                ],
                500
            );

        }

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
        $this->validate(
            $request,
            [
                'uid' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'uid' => 'User ID',
                ]
            )
        );

        $userID = $request->uid;

        if (Auth::user()->uid === $userID) {
            return response()->json(
                [
                    'message' => 'Admin user can not delete himself',
                    'errors' => []
                ],
                500
            );
        }

        DB::beginTransaction();

        try {
            $user = User::where('uid', $userID)->first();

            if (is_null($user)) {
                throw new \Exception('User does not exist');
            }

            Profile::where('user_id', $user->id)->delete();
            Invite::where('user_id', $user->id)->delete();
            DebitCard::where('user_id', $user->id)->delete();
            Verification::where('user_id', $user->id)->delete();
            PasswordReset::where('email', $user->email)->delete();
            SocialNetworkAccount::where('user_id', $user->id)->delete();
            ZantecoinTransaction::where('user_id', $user->id)->delete();
            Wallet::where('user_id', $user->id)->delete();

            // Documents
            $documents = Document::where('user_id', $user->id)->get();
            foreach ($documents as $document) {
                if (Storage::exists($document->file_path)) {
                    Storage::delete($document->file_path);
                }
            }
            Document::where('user_id', $user->id)->delete();

            $user->delete();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error deleting user',
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
     * Wallets operations
     *
     * @return View
     */
    public function wallet()
    {

        // ICO Table
        $ico = new Ico();

        $currentDate = time();

        $icoInfo = [];

        foreach ($ico->getParts() as $icoPart) {
            $startDate = $icoPart->getStartDate();
            $endDate = $icoPart->getEndDate();

            $weiReceived = Contribution::where('time_stamp', '>=', strtotime($startDate))
                ->where('time_stamp', '<', strtotime($endDate))
                ->get()
                ->sum('amount');

            $icoName = $icoPart->getName();

            if (strtotime($icoPart->getStartDate()) < $currentDate) {
                $icoName .= ' (started ' . $startDate . ')';
            } else {
                $icoName .= ' (starts ' . $startDate . ')';
            }

            if ($icoPart->getID() === $ico->getActivePart()->getID()) {
                $icoName .= ' - current';
            }

            $icoInfo[] = [
                'name' => $icoName,
                'limit' => number_format($icoPart->getLimit(), 0, '.', ' '),
                'balance' => number_format($icoPart->getBalance(), 0, '.', ' '),
                'eth' => RateCalculator::weiToEth($weiReceived)
            ];
        }

        // Grant balance and limits
        $grant = new Grant();

        $foundationGranted = ZantecoinTransaction::where('transaction_type', ZantecoinTransaction::TRANSACTION_ADD_FOUNDATION_ZNX)->get()->sum('amount');

        $marketingBalance = $grant->marketingPool()->getLimit();
        $companyBalance = $grant->companyPool()->getLimit() - $foundationGranted;

        $grantBalance = [
            'marketing_balance' => $marketingBalance,
            'company_balance' => $companyBalance,
        ];


        return view(
            'admin.wallet',
            [
                'ico' => $icoInfo,
                'balance' => $grantBalance
            ]
        );
    }

    /**
     * Grant Marketing Coins
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function grantMarketingCoins(Request $request)
    {
        $this->validate(
            $request,
            [
                'address' => 'required|string',
                'amount' => 'required|integer',
            ],
            ValidationMessages::getList(
                [
                    'address' => 'Beneficiary Address',
                    'amount' => 'Grant ZNX Amount',
                ]
            )
        );

        $address = $request->address;
        $amount = (int)$request->amount;

        $transaction = GrantCoinsTransaction::create(
            [
                'address' => $address,
                'amount' => $amount,
                'type' => GrantCoinsTransaction::GRANT_MARKETING_COINS,
            ]
        );

        try {

            $operationID = EtheriumApi::getCoinsOID($transaction->type, $amount, $address);

            $transaction->operation_id = $operationID;
            $transaction->save();

            $transactionStatus = EtheriumApi::checkCoinsStatus($operationID);

            switch ($transactionStatus) {
                case 'success':
                    $transaction->status = GrantCoinsTransaction::STATUS_COMPLETE;
                    break;

                case 'failure':
                    $transaction->status = GrantCoinsTransaction::STATUS_FAILED;
                    break;

                default:
                    $transaction->status = GrantCoinsTransaction::STATUS_IN_PROGRESS;
            }

            $transaction->save();

        } catch (\Exception $e) {
            $transaction->status = GrantCoinsTransaction::STATUS_FAILED;
            $transaction->save();

            return response()->json(
                [
                    'message' => 'Error granting Marketing Coins',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            []
        );
    }

    /**
     * Grant Company Coins
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function grantCompanyCoins(Request $request)
    {
        $this->validate(
            $request,
            [
                'address' => 'required|string',
                'amount' => 'required|integer',
            ],
            ValidationMessages::getList(
                [
                    'address' => 'Beneficiary Address',
                    'amount' => 'Grant ZNX Amount',
                ]
            )
        );

        $address = $request->address;
        $amount = (int)$request->amount;

        $transaction = GrantCoinsTransaction::create(
            [
                'address' => $address,
                'amount' => $amount,
                'type' => GrantCoinsTransaction::GRANT_COMPANY_COINS,
            ]
        );

        try {

            $operationID = EtheriumApi::getCoinsOID($transaction->type, $amount, $address);

            $transaction->operation_id = $operationID;
            $transaction->save();

            $transactionStatus = EtheriumApi::checkCoinsStatus($operationID);

            switch ($transactionStatus) {
                case 'success':
                    $transaction->status = GrantCoinsTransaction::STATUS_COMPLETE;
                    break;

                case 'failure':
                    $transaction->status = GrantCoinsTransaction::STATUS_FAILED;
                    break;

                default:
                    $transaction->status = GrantCoinsTransaction::STATUS_IN_PROGRESS;
            }

            $transaction->save();

        } catch (\Exception $e) {
            $transaction->status = GrantCoinsTransaction::STATUS_FAILED;
            $transaction->save();

            return response()->json(
                [
                    'message' => 'Error granting Company Coins',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            []
        );
    }


    /**
     * Search ICO transactions
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function searchIcoTransactions(Request $request)
    {
        $this->validate(
            $request,
            [
                'part_filter' => 'string|nullable',
                'status_filter' => 'array',
                'page' => 'integer|min:1',
                'sort_index' => 'integer',
                'sort_order' => 'in:asc,desc',
            ],
            ValidationMessages::getList(
                [
                    'part_filter' => 'ICO Part',
                    'status_filter' => 'Status Filter',
                    'page' => 'Page',
                    'sort_index' => 'Sort Column',
                    'sort_order' => 'Sort Order',
                ]
            )
        );

        try {

            $filters = [
                'grant_type_filter' => GrantCoinsTransaction::GRANT_ICO_COINS,
                'znx_type_filter' => ZantecoinTransaction::getIcoTransactionTypes(),
                'part_filter' => $request->part_filter,
                'status_filter' => $request->status_filter,
                'page' => $request->page,
            ];

            $sort = [
                'sort_index' => $request->sort_index,
                'sort_order' => $request->sort_order,
            ];

            $transactionsList = Transactions::searchICOTransactions($filters, $sort);

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error while searching transactions',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            $transactionsList
        );
    }

    /**
     * Search Foundation transactions
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function searchFoundationTransactions(Request $request)
    {
        $this->validate(
            $request,
            [
                'part_filter' => 'string|nullable',
                'status_filter' => 'array',
                'page' => 'integer|min:1',
                'sort_index' => 'integer',
                'sort_order' => 'in:asc,desc',
            ],
            ValidationMessages::getList(
                [
                    'part_filter' => 'ICO Part',
                    'status_filter' => 'Status Filter',
                    'page' => 'Page',
                    'sort_index' => 'Sort Column',
                    'sort_order' => 'Sort Order',
                ]
            )
        );

        try {

            $filters = [
                'grant_type_filter' => GrantCoinsTransaction::GRANT_COMPANY_COINS,
                'znx_type_filter' => ZantecoinTransaction::getFoundationTransactionTypes(),
                'part_filter' => $request->part_filter,
                'status_filter' => $request->status_filter,
                'page' => $request->page,
            ];

            $sort = [
                'sort_index' => $request->sort_index,
                'sort_order' => $request->sort_order,
            ];

            $transactionsList = Transactions::searchICOTransactions($filters, $sort);

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error while searching transactions',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            $transactionsList
        );
    }

}
