<?php

namespace Modules\Ticket\App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Ticket\App\Notifications\TicketStatusChangedNotification;
use Modules\Ticket\Events\TicketStatusChanged;

class SendTicketStatusChangedNotification
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
    public function handle(TicketStatusChanged $event): void
    {
        $event->ticket->user->notify(new TicketStatusChangedNotification(
            $event->ticket,
            $event->oldStatus,
            $event->newStatus,
        ));
    }
}
