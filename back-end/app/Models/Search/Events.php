<?php

namespace App\Models\Search;


use App\Models\DB\MailEvent;
use App\Models\Services\MailService;

class Events
{
    /**
     * Events per page
     */
    const EVENTS_PER_PAGE = 25;

    /**
     * Search transactions info for Admin -> Grant tables
     *
     * @param array $filters
     * @param array $sort
     *
     * @return array
     */
    public static function searchMailEvents($filters, $sort)
    {
        $typeFilter = $filters['type_filter'] ?? [];
        $statusFilter = $filters['status_filter'] ?? [];
        $page = $filters['page'] ?? 1;
        $sortIndex = $sort['sort_index'] ?? 0;
        $sortOrder = $sort['sort_order'] ?? 'desc';

        $mailEvents = MailEvent::all();

        if (count($typeFilter) > 0) {
            $mailEvents = $mailEvents->whereIn('event_type', $typeFilter);
        }

        if (count($statusFilter) > 0) {
            $mailEvents = $mailEvents->whereIn('status', $statusFilter);
        }

        // Sort
        switch ($sortIndex) {
            case 0:
                $sortColumn = 'created_at';
                break;

            default:
                $sortColumn = 'id';
        }

        if ($sortOrder == 'asc') {
            $mailEvents = $mailEvents->sortBy($sortColumn);
        } else {
            $mailEvents = $mailEvents->sortByDesc($sortColumn);
        }

        // Paginator
        $totalPages = ceil(count($mailEvents) / self::EVENTS_PER_PAGE);
        $eventsCollection = $mailEvents->slice(($page - 1) * self::EVENTS_PER_PAGE, self::EVENTS_PER_PAGE)->values();

        $eventsList = [];

        foreach ($eventsCollection as $event) {
            $eventsList[] = [
                'id' => $event->id,
                'date' => $event->created_at->format('m/d/y H:i'),
                'event' => MailService::getEventName($event->event_type),
                'to' => $event->mail_to,
                'status' => MailService::getEventStatus($event->status),
                'isSuccess' => MailService::checkEventStatus($event->status)
            ];
        }

        return [
            'eventsList' => $eventsList,
            'paginator' => [
                'currentPage' => $page,
                'totalPages' => $totalPages
            ]
        ];
    }

}
