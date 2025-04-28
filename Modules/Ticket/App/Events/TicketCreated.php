<?php

namespace Modules\Ticket\App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Ticket\App\Models\Ticket;

class TicketCreated
{
    use Dispatchable, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {

        $this->ticket = $ticket;
    }
}