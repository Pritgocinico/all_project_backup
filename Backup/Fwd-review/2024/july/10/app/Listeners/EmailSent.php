<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\RecipientEmailEvent;

class EmailSent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $recipient_id = $event->sent_email->getHeader('X-Model-ID');
        $event_email = new RecipientEmailEvent();
        $event_email->recipient_id   = $recipient_id;
        $event_email->event = 'Email Sent';
        $event_email->sent_email = $event->sent_email;
        $event_email->save();
    }
}
