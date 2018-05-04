<?php

namespace App\Http\Controllers;


use App\Models\Search\Events;
use App\Models\Services\MailService;
use App\Models\Validation\ValidationMessages;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * ServiceController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
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
     * Search EmMail Events
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

}
