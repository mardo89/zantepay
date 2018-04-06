<?php

namespace App\Http\Controllers;

use App\Models\Search\Transactions;
use App\Models\DB\GrantCoinsTransaction;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Services\AccountsService;
use App\Models\Services\IcoService;
use App\Models\Services\TokensService;
use App\Models\Validation\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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

        try {

            AccountsService::changeRole($request->uid, $request->role);

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => $e->getMessage(),
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

        DB::beginTransaction();

        try {

            AccountsService::removeAccount($request->uid);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => $e->getMessage(),
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

        return view(
            'admin.wallet',
            [
                'ico' => IcoService::getInfo(),
                'balance' => TokensService::getGrantBalance()
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

        try {

            TokensService::grantMarketingTokens($request->address, $request->amount);

        } catch (\Exception $e) {

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

        try {

            TokensService::grantCompanyTokens($request->address, $request->amount);

        } catch (\Exception $e) {

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
                'grant_type_filter' => GrantCoinsTransaction::GRANT_ICO_TOKENS,
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
                'grant_type_filter' => GrantCoinsTransaction::GRANT_COMPANY_TOKENS,
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
