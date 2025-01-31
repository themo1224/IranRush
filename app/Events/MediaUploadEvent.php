<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MediaUploadEvent
{
    use Dispatchable, SerializesModels, InteractsWithSockets;

    public $mediaType;
    public $mediaId;

    public function __construct($mediaType, $mediaId)
    {
        Log::info("AssetUploaded Event Fired: Asset ID {$mediaId}, Type: {$mediaType}");
        $this->mediaType = $mediaType;
        $this->mediaId = $mediaId;
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
