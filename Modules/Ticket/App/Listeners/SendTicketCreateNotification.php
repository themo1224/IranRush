<?php

namespace Modules\Ticket\App\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Ticket\App\Notifications\TicketCreatedNotification;
use Modules\Ticket\Events\TicketCreated;

class SendTicketCreateNotification
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
    public function handle(TicketCreated $event): void
    {
        $event->ticket->user->notify(new TicketCreatedNotification( $event->ticket));
        $admins = User::role('admin')->get();

        foreach($admins as $admin){
            $admin->notify(new TicketCreatedNotification($event->ticket));
        }
    }
}
