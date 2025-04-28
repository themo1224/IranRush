<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\Notification\App\Events\MediaUploadEvent;
use Modules\Notification\App\Listeners\SendMediaUploadedNotification;
use Modules\Ticket\App\Events\TicketCreated;
use Modules\Ticket\App\Listeners\SendTicketCreateNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        MediaUploadEvent::class => [
            SendMediaUploadedNotification::class,
        ],
        TicketCreated::class => [
            SendTicketCreateNotification::class,
        ],

    ];

    public function boot()
    {
        parent::boot();

        // Explicit event listener registration
        Event::listen(
            MediaUploadEvent::class,
            [SendMediaUploadedNotification::class, 'handle']
        );
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
