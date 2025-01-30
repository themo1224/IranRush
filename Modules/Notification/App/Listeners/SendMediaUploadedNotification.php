<?php

namespace Modules\Notification\App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Auth\App\Models\User;
use Modules\Notification\App\Notifications\AssetUploadedNotification;

class SendMediaUploadedNotification
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        $admins = User::role('admin')->get();

        // Send notification to each admin
        foreach ($admins as $admin) {
            $admin->notify(new AssetUploadedNotification($event->mediaType, $event->mediaId));
        }
    }
}
