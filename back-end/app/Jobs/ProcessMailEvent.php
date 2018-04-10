<?php

namespace App\Jobs;

use App\Models\DB\MailEvent;
use App\Models\Services\MailService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class ProcessMailEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int $eventID
     */
    protected $eventID;

    /**
     * Create a new job instance.
     *
     * @param MailEvent $event
     *
     * @return void
     */
    public function __construct($eventID)
    {
        $this->eventID = $eventID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $event = MailEvent::find($this->eventID);

        if (is_null($event) || $this->attempts() > 1) {
            $this->release();
        }

        try {

            $mailObj = MailService::getMailObj($event);

            Mail::to($event->mail_to)->send($mailObj);

            $event->status = MailEvent::EVENT_STATUS_SENT;


        } catch (\Exception $e) {

            $event->status = MailEvent::EVENT_STATUS_FAILED;
            $event->error = $e->getMessage();
        }

        $event->save();
    }
}
