<?php

namespace App\Models\Search;



use App\Models\DB\User;
use App\Models\Services\UsersService;

class Users
{
    /**
     * Users per page
     */
    private static $usersPerPage = 25;

    /**
     * Search transactions info for Admin -> Grant tables
     *
     * @param array $filters
     * @param array $sort
     *
     * @return array
     */
    public static function searchUsers($filters, $sort)
    {
        $roleFilter = $filters['role_filter'] ?? [];
        $statusFilter = $filters['status_filter'] ?? [];
        $referrerFilter = $filters['referrer_filter'] ?? [];
        $nameFilter = $filters['name_filter'] ?? '';
        $dateFromFilter = $filters['date_from_filter'] ?? '';
        $dateToFilter = $filters['date_to_filter'] ?? '';
        $page = $filters['page'] ?? 1;
        $sortIndex = $sort['sort_index'] ?? 0;
        $sortOrder = $sort['sort_order'] ?? 'desc';

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

        if ($dateFromFilter != '') {
            $queryBuilder->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($dateFromFilter)));
        }

        if ($dateToFilter != '') {
            $queryBuilder->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($dateToFilter)));
        }

        // sort
        switch ($sortIndex) {
            case 0:
                $sortColumn = 'email';
                break;

            case 1:
                $sortColumn = 'first_name';
                break;

            case 2:
                $sortColumn = 'created_at';
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
                'registered' => $user->created_at->format('m/d/Y'),
                'status' => UsersService::getUserStatus($user->status),
                'role' => UsersService::getUserRole($user->role),
                'hasReferrals' => $user->referrals->count() > 0 ? 'YES' : 'NO',
                'profileLink' => action('ManagerController@profile', ['uid' => $user->uid]),
            ];
        }

        $totalFound = count($usersList);

        // Paginator
	    $totalPages = 1;

	    if (self::$usersPerPage > 0) {
		    $totalPages = ceil(count($usersList) / self::$usersPerPage);
		    $usersList = array_slice($usersList, ($page - 1) * self::$usersPerPage, self::$usersPerPage);
	    }

        return [
        	'totalFound' => $totalFound,
            'usersList' => $usersList,
            'paginator' => [
                'currentPage' => $page,
                'totalPages' => $totalPages
            ]
        ];
    }

    /**
     * Return total number of registered users
     *
     * @return int
     */
    public static function getTotalUsers() {
        return User::count();
    }

	/**
	 * Search users for export to csv
	 *
	 * @param array $filters
	 * @param array $sort
	 *
	 * @return array
	 */
	public static function importUsers($filters, $sort) {

		self::$usersPerPage = 0;

		$searchResults = self::searchUsers($filters, $sort);

		$exportList = [];

		foreach ($searchResults['usersList'] as $user) {
			$exportList[] = [
				'Email' => $user['email']
			];
		}

		$df = fopen("php://output", 'w');

		fputcsv($df, array_keys(reset($exportList)));

		foreach($exportList as $row)     {
			fputcsv($df, $row);
		}

		header('Content-Type: text/csv');
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=users.csv");

		fpassthru($df);
	}

}
