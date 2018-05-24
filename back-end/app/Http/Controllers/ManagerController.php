<?php

namespace App\Http\Controllers;

use App\Models\DB\ZantecoinTransaction;
use App\Models\Search\Users;
use App\Models\Services\AccountsService;
use App\Models\Services\DocumentsService;
use App\Models\Services\ProfilesService;
use App\Models\Services\UsersService;
use App\Models\Services\VerificationService;
use App\Models\Services\WalletsService;
use App\Models\Wallet\Currency;
use App\Models\DB\User;
use App\Models\Validation\ValidationMessages;
use App\Models\Wallet\Ico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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

            $profileInfo = AccountsService::getInfo($request->uid);

        } catch (\Exception $e) {

            return redirect('admin/users');

        }

        return view(
            $this->getViewPrefix() . 'profile',
            $profileInfo
        );
    }

//    /**
//     * Approve document
//     *
//     * @param Request $request
//     *
//     * @return Json
//     */
//    public function approveDocument(Request $request)
//    {
//        $this->validate(
//            $request,
//            [
//                'uid' => 'required|string',
//                'type' => 'required|integer',
//            ],
//            ValidationMessages::getList(
//                [
//                    'uid' => 'User ID',
//                    'type' => 'Document Type',
//                ]
//            )
//        );
//
//        DB::beginTransaction();
//
//        try {
//
//            $verificationStatus = DocumentsService::approveUserDocuments($request->uid, $request->type);
//
//
//        } catch (\Exception $e) {
//
//            DB::rollback();
//
//            return response()->json(
//                [
//                    'message' => 'Error approving documents',
//                    'errors' => []
//                ],
//                500
//            );
//
//        }
//
//        DB::commit();
//
//        return response()->json(
//            [
//                'status' => $verificationStatus
//            ]
//        );
//    }
//
//    /**
//     * Decline document
//     *
//     * @param Request $request
//     *
//     * @return Json
//     */
//    public function declineDocument(Request $request)
//    {
//        $this->validate(
//            $request,
//            [
//                'uid' => 'required|string',
//                'type' => 'required|integer',
//                'reason' => 'required|string',
//            ],
//            ValidationMessages::getList(
//                [
//                    'uid' => 'User ID',
//                    'type' => 'Document Type',
//                    'reason' => 'Decline Reason',
//                ]
//            )
//        );
//
//        DB::beginTransaction();
//
//        try {
//
//            $verificationStatus = DocumentsService::declineUserDocuments($request->uid, $request->type, $request->reason);
//
//        } catch (\Exception $e) {
//            DB::rollback();
//
//            return response()->json(
//                [
//                    'message' => 'Error declining documents',
//                    'errors' => []
//                ],
//                500
//            );
//
//        }
//
//        DB::commit();
//
//        return response()->json(
//            [
//                'status' => $verificationStatus
//            ]
//        );
//    }
//
//    /**
//     * Show user document
//     *
//     * @param Request $request
//     *
//     * @return File
//     */
//    public function document(Request $request)
//    {
//        $this->validate($request, [
//            'did' => 'required|string',
//        ]);
//
//        try {
//
//            $documentInfo = DocumentsService::getDocumentInfo($request->did);
//
//        } catch(\Exception $e) {
//
//            return redirect('admin/users');
//
//        }
//
//        return response()->file(
//            $documentInfo['documentFilePath'],
//            [
//                'Content-Type' => $documentInfo['documentMimeType']
//            ]
//        );
//    }

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

        DB::beginTransaction();

        try {

            $znxAmount = WalletsService::addIcoZnx($request->uid, $request->amount);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'There has been an error with transfer of ' . $request->amount . ' ZNX from ICO pool.',
                    'errors' => []
                ],
                500
            );

        }

        DB::commit();

        return response()->json(
            [
                'totalAmount' => $znxAmount
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

        DB::beginTransaction();

        try {

            $znxAmount = WalletsService::addFoundationZnx($request->uid, $request->amount);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'There has been an error with transfer of ' . $request->amount . ' ZNX from ICO pool.',
                    'errors' => []
                ],
                500
            );

        }

        DB::commit();

        return response()->json(
            [
                'totalAmount' => $znxAmount
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

            $user = AccountsService::getUserByID($request->uid);

            ProfilesService::updateWalletAddress($user, $request->address, $request->currency);

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
        return AccountsService::getActiveUser()->isAdmin() ? 'admin.' : 'manager.';
    }
}
