<?php

namespace App\Http\Controllers;


use App\Models\Search\Events;
use App\Models\Services\AccountsService;
use App\Models\Services\MailService;
use App\Models\Services\VerificationService;
use App\Models\Validation\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\Util\Json;

class ServiceController extends Controller
{
    /**
     * ServiceController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
        $this->middleware('protect.auth');
    }

    /**
     * Mail Events
     *
     * @return View
     */
    public function mailEvents()
    {
        return view(
            'service.mail-events',
            [
                'eventTypes' => MailService::getEventTypes(),
                'eventStatuses' => MailService::getEventStatuses(),
            ]
        );

    }

    /**
     * Search Mail Events
     *
     * @param Request $request
     *
     * @return View
     */
    public function searchMailEvents(Request $request)
    {
        $this->validate(
            $request,
            [
                'status_filter' => 'array|bail',
                'type_filter' => 'array|bail',
                'page' => 'integer|min:1|bail',
                'sort_index' => 'integer|bail',
                'sort_order' => 'in:asc,desc|bail',
            ],
            ValidationMessages::getList(
                [
                    'status_filter' => 'Status Filter',
                    'type_filter' => 'Referrer Filter',
                    'page' => 'Page',
                    'sort_index' => 'Sort Column',
                    'sort_order' => 'Sort Order',
                ]
            )
        );

        try {

            $filters = [
                'type_filter' => $request->type_filter,
                'status_filter' => $request->status_filter,
                'page' => $request->page,
            ];

            $sort = [
                'sort_index' => $request->sort_index,
                'sort_order' => $request->sort_order,
            ];

            $eventsList = Events::searchMailEvents($filters, $sort);;

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error while searching email events',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            $eventsList
        );

    }

    /**
     * Process Mail Events
     *
     * @param Request $request
     *
     * @return View
     */
    public function processMailEvent(Request $request)
    {
        $this->validate(
            $request,
            [
                'id' => 'required|integer|bail',
            ],
            ValidationMessages::getList(
                [
                    'event' => 'Event',
                ]
            )
        );

        try {

            MailService::resendEvent($request->id);

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error while processing email event',
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
     * Verification Events
     *
     * @return View
     */
    public function verification()
    {
        return view(
            'service.verification',
            [
                'verificationStatuses' => VerificationService::getVerificationStatuses(),
            ]
        );

    }

    /**
     * Search Verification Events
     *
     * @param Request $request
     *
     * @return View
     */
    public function searchVerificationInfo(Request $request)
    {
        $this->validate(
            $request,
            [
                'status_filter' => 'array|bail',
                'page' => 'integer|min:1|bail',
                'sort_index' => 'integer|bail',
                'sort_order' => 'in:asc,desc|bail',
            ],
            ValidationMessages::getList(
                [
                    'status_filter' => 'Status Filter',
                    'page' => 'Page',
                    'sort_index' => 'Sort Column',
                    'sort_order' => 'Sort Order',
                ]
            )
        );

        try {

            $filters = [
                'status_filter' => $request->status_filter,
                'page' => $request->page,
            ];

            $sort = [
                'sort_index' => $request->sort_index,
                'sort_order' => $request->sort_order,
            ];

            $eventsList = Events::searchVerificationInfo($filters, $sort);;

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error while searching verification info',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            $eventsList
        );

    }

    /**
     * Reset verification
     *
     * @param Request $request
     *
     * @return Json
     */
    public function resetVerification(Request $request)
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

            $user = AccountsService::getUserByID($request->uid);

            $verificationStatus = VerificationService::resetVerification($user);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error resetting verification',
                    'errors' => []
                ],
                500
            );

        }

        DB::commit();

        return response()->json(
            [
                'verificationStatus' => $verificationStatus
            ]
        );
    }

}
