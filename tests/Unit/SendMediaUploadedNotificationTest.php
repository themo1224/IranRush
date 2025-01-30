<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Modules\Auth\App\Models\User;
use Modules\Notification\App\Events\MediaUploadEvent;
use Modules\Notification\App\Listeners\SendMediaUploadedNotification;
use Modules\Notification\App\Notifications\AssetUploadedNotification;

class SendMediaUploadedNotificationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_notification_is_sent_to_admins()
    {
        Notification::fake();

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $event = new MediaUploadEvent('video', 1);

        $listener = new SendMediaUploadedNotification();
        $listener->handle($event);

        Notification::assertSentTo(
            [$admin],
            AssetUploadedNotification::class
        );

        // Step 7: Assert that the regular user did not receive the notification
        // Notification::assertNotSentTo(
        //     [$user], // The regular user
        //     AssetUploadedNotification::class // The notification class
        // );
    }
}
