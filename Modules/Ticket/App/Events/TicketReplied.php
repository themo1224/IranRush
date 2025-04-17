<?php

namespace Modules\Ticket\App\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Ticket\App\Models\Ticket;

class TicketReplied
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public $ticket;
    public $reply;
    public function __construct(Ticket $ticket , $reply)
    {
        $this->ticket= $ticket;
        $this->reply= $reply;
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
