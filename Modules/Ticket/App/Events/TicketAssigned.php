<?php

namespace Modules\Ticket\App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Modules\Ticket\App\Models\Ticket;

class TicketAssigned
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public $assignedTo;
    public $ticket;
    
    public function __construct(Ticket $ticket, User $assignedTo)
    {
        $this->ticket = $ticket;
        $this->assignedTo = $assignedTo;
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
