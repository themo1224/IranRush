<?php

namespace App\Listeners;

use App\Events\MediaUploadEvent;
use App\Notifications\AssetUploadedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Modules\Auth\App\Models\User;

class SendMediaUploadedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(MediaUploadEvent $event): void
    {

        $admins = User::first();
        dd($admins);
        Log::info("SendAssetUploadedNotification Listener Triggered: Asset ID {$event->mediaId}");

        // Send notification to each admin
        foreach ($admins as $admin) {
            $admin->notify(new AssetUploadedNotification($event->mediaType, $event->mediaId));
        }
    }
}
