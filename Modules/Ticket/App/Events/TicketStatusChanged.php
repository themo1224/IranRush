<?php

namespace Modules\Ticket\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Ticket\App\Models\Ticket;

class TicketStatusChanged
{
    use Dispatchable, SerializesModels;

    public $ticket;
    public $oldStatus;
    public $newStatus;

    public function __construct(Ticket $ticket, $oldStatus, $newStatus)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}

