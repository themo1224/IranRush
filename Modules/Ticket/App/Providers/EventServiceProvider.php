<?php

namespace Modules\Ticket\App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Ticket\App\Listeners\SendTicketCreateNotification;
use Modules\Ticket\App\Events\TicketCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TicketCreated::class => [
            SendTicketCreateNotification::class,
        ],
    ];
}