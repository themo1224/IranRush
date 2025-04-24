<?php

namespace Modules\Ticket\App\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Email\App\Services\EmailService;
use Modules\Ticket\App\Notifications\TicketCreatedNotification;
use Modules\Ticket\App\Events\TicketCreated;

class SendTicketCreateNotification
{
    /**
     * Create the event listener.
     */
    protected $emailService;

    public function __construct(EmailService  $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Handle the event.
     */
    public function handle(TicketCreated $event): void
    {
        $event->ticket->user->notify(new TicketCreatedNotification($event->ticket, $this->emailService));
        $admins = User::role('admin')->get();

        foreach($admins as $admin){
            $admin->notify(new TicketCreatedNotification($event->ticket, $this->emailService));
        }
    }
}
