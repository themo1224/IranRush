<?php

namespace Modules\Ticket\App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Ticket\App\Listeners\SendTicketCreateNotification;
use Illuminate\Support\Facades\Event;
use Modules\Ticket\App\Events\TicketCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Register the event and listener here
        TicketCreated::class => [
            SendTicketCreateNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}