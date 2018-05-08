<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAccessException;
use App\Exceptions\UserNotFoundException;
use App\Models\Search\Transactions;
use App\Models\DB\GrantCoinsTransaction;
use App\Models\Services\AccountsService;
use App\Models\Services\IcoService;
use App\Models\Services\TokensService;
use App\Models\Services\TransactionsService;
use App\Models\Services\UsersService;
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
        $this->middleware('protect.auth');
    }

    /**
     * Change user role
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
                'uid' => 'required|string|bail',
                'role' => 'required|integer|bail',
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

            $user = AccountsService::getUserByID($request->uid);

            UsersService::changeUserRole($user, $request->role);

        } catch (\Exception $e) {

            $message = 'Error updating user role';

            if($e instanceof UserNotFoundException){
                $message = $e->getMessage();
            }

            if($e instanceof UserAccessException){
                $message = 'Admin user can not update role for himself';
            }

            return response()->json(
                [
                    'message' => $message,
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
                'uid' => 'required|string|bail',
            ],
            ValidationMessages::getList(
                [
                    'uid' => 'User ID',
                ]
            )
        );

        DB::beginTransaction();

        try {

            AccountsService::removeUser($request->uid);

        } catch (\Exception $e) {
            DB::rollback();

            $message = 'Error deleting user';

            if($e instanceof UserNotFoundException){
                $message = $e->getMessage();
            }

            if($e instanceof UserAccessException){
                $message = 'Admin user can not delete himself';
            }

            return response()->json(
                [
                    'message' => $message,
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
                'ico' => (new IcoService())->getAdminInfo(),
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
                'address' => 'required|string|bail',
                'amount' => 'required|integer|bail',
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
                'address' => 'required|string|bail',
                'amount' => 'required|integer|bail',
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
                'part_filter' => 'string|nullable|bail',
                'status_filter' => 'array|bail',
                'page' => 'integer|min:1|bail',
                'sort_index' => 'integer|bail',
                'sort_order' => 'in:asc,desc|bail',
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
                'znx_type_filter' => TransactionsService::getIcoTransactionTypes(),
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
     * Search Marketing transactions
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function searchMarketingTransactions(Request $request)
    {
        $this->validate(
            $request,
            [
                'part_filter' => 'string|nullable|bail',
                'status_filter' => 'array|bail',
                'page' => 'integer|min:1|bail',
                'sort_index' => 'integer|bail',
                'sort_order' => 'in:asc,desc|bail',
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
                'grant_type_filter' => GrantCoinsTransaction::GRANT_MARKETING_TOKENS,
                'znx_type_filter' => TransactionsService::getMarketingTransactionTypes(),
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
     * Search Company transactions
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function searchCompanyTransactions(Request $request)
    {
        $this->validate(
            $request,
            [
                'part_filter' => 'string|nullable|bail',
                'status_filter' => 'array|bail',
                'page' => 'integer|min:1|bail',
                'sort_index' => 'integer|bail',
                'sort_order' => 'in:asc,desc|bail',
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
                'znx_type_filter' => TransactionsService::getCompanyTransactionTypes(),
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
