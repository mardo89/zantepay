<?php

namespace App\Models\Search;


use App\Models\DB\MailEvent;
use App\Models\DB\Verification;
use App\Models\Services\MailService;
use App\Models\Services\VerificationService;

class Events
{
    /**
     * Events per page
     */
    const EVENTS_PER_PAGE = 25;

    /**
     * Search for mail events
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

    /**
     * Search for users verification info
     *
     * @param array $filters
     * @param array $sort
     *
     * @return array
     */
    public static function searchVerificationInfo($filters, $sort)
    {
        $statusFilter = $filters['status_filter'] ?? [];
        $page = $filters['page'] ?? 1;
        $sortIndex = $sort['sort_index'] ?? 0;
        $sortOrder = $sort['sort_order'] ?? 'desc';

        $verification = Verification::with('user')->get();

        if (count($statusFilter) > 0) {
            $verification = $verification->whereIn('status', $statusFilter);
        }

        // Sort
        switch ($sortIndex) {
            case 0:
                $sortColumn = 'user.email';
                break;

            default:
                $sortColumn = 'id';
        }

        if ($sortOrder == 'asc') {
            $verification = $verification->sortBy($sortColumn);
        } else {
            $verification = $verification->sortByDesc($sortColumn);
        }

        // Paginator
        $totalPages = ceil(count($verification) / self::EVENTS_PER_PAGE);
        $verificationCollection = $verification->slice(($page - 1) * self::EVENTS_PER_PAGE, self::EVENTS_PER_PAGE)->values();

        $verificationInfo = [];

        foreach ($verificationCollection as $userVerification) {
            $verificationInfo[] = [
                'id' => $userVerification->user->uid,
                'user' => $userVerification->user->email,
                'status' => VerificationService::verificationStatus($userVerification),
                'canReset' => VerificationService::verificationInProgress($userVerification)
            ];
        }

        return [
            'verificationInfo' => $verificationInfo,
            'paginator' => [
                'currentPage' => $page,
                'totalPages' => $totalPages
            ]
        ];
    }

}
