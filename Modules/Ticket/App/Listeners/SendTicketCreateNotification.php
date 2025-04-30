<?php

namespace Modules\Ticket\App\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Modules\Email\App\Services\EmailService;
use Modules\Ticket\App\Notifications\TicketCreatedNotification;
use Modules\Ticket\App\Events\TicketCreated;

class SendTicketCreateNotification
{
    /**
     * Create the event listener.
     */
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function handle(TicketCreated $event): void
    {
        Log::info("Sending notification to ticket owner: " . $event->ticket);
        $event->ticket->user->notify(new TicketCreatedNotification($event->ticket));
        
        $admins = User::role('admin')->get();
        foreach($admins as $admin){
            Log::info("Sending notification to admin: " . $admin->email);
            $admin->notify(new TicketCreatedNotification($event->ticket));
        }
    }
}
