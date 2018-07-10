<?php

namespace App\Models\Search;


use App\Models\DB\GrantCoinsTransaction;
use App\Models\DB\User;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Services\TokensService;

class Transactions
{
    /**
     * Transactions per page
     */
    const TRANSACTIONS_PER_PAGE = 25;

    /**
     * Search transactions info for Admin -> Grant tables
     *
     * @param array $filters
     * @param array $sort
     *
     * @return array
     */
    public static function searchICOTransactions($filters, $sort)
    {
        $grantTypeFilter = $filters['grant_type_filter'] ?? 0;
        $znxTypeFilter = $filters['znx_type_filter'] ?? [];
        $partFilter = $filters['part_filter'] ?? '';
        $statusFilter = $filters['status_filter'] ?? [];
        $page = $filters['page'] ?? 1;
        $sortIndex = $sort['sort_index'] ?? 0;
        $sortOrder = $sort['sort_order'] ?? 'desc';

        $users = User::get();
        $grantCoinsTransactions = GrantCoinsTransaction::where('type', $grantTypeFilter)->get();
        $znxTransactions = ZantecoinTransaction::whereIn('transaction_type', $znxTypeFilter)->get();

        $usersZnxTransactions = [];

        foreach ($znxTransactions as $znxTransaction) {
        	$usersZnxTransactions[$znxTransaction->user_id][] = $znxTransaction;
        }

        // Generate users list
        $usersList = [];

        foreach ($users as $user) {

            $userZnxTransactions = isset($usersZnxTransactions[$user->id]) ? collect($usersZnxTransactions[$user->id]) : collect([]);

            if ($partFilter != '') {
                $icoAmount = $userZnxTransactions->where('ico_part', $partFilter)->sum('amount');
            } else {
	            $icoAmount = $userZnxTransactions->sum('amount');
            }

            if ($icoAmount == 0) {
                continue;
            }

            $grantCoinTransaction = $grantCoinsTransactions->where('address', $user->profile->eth_wallet)->first();
            $transactionStatus = $grantCoinTransaction ? TokensService::getTransactionStatus($grantCoinTransaction->status) : '';

            if (count($statusFilter) > 0 && !in_array($transactionStatus, $statusFilter)) {
                continue;
            }

            $userName = $user->first_name . ' ' . $user->last_name;

            $usersList[] = [
	            'uid' => $user->uid,
                'user' => trim($userName) != '' ? $userName : $user->email,
                'address' => $user->profile->eth_wallet,
                'amount' => $icoAmount,
                'status' => $transactionStatus
            ];

        }

        // Sort
        $usersCollection = collect($usersList);

        switch ($sortIndex) {
            case 0:
                $sortColumn = 'user';
                break;

            case 1:
                $sortColumn = 'address';
                break;

            case 2:
                $sortColumn = 'amount';
                break;

            default:
                $sortColumn = 'user';
        }

        if ($sortOrder == 'asc') {
            $usersCollection = $usersCollection->sortBy($sortColumn);
        } else {
            $usersCollection = $usersCollection->sortByDesc($sortColumn);
        }

        // Paginator
        $totalPages = ceil(count($usersList) / self::TRANSACTIONS_PER_PAGE);
        $transactionsList = $usersCollection->slice(($page - 1) * self::TRANSACTIONS_PER_PAGE, self::TRANSACTIONS_PER_PAGE)->values();

        return [
            'transactionsList' => $transactionsList,
            'paginator' => [
                'currentPage' => $page,
                'totalPages' => $totalPages
            ]
        ];
    }

    /**
     * Search total amount of the transactions
     *
     * @param array $transactionTypes
     *
     * @return int
     */
    public static function searchTransactionsAmount($transactionTypes)
    {
        return ZantecoinTransaction::whereIn('transaction_type', $transactionTypes)->get()->sum('amount');
    }

}
