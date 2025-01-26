<?php

namespace Modules\Notification\App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;


class MediaUploadEvent
{
    use Dispatchable, SerializesModels;

    public $mediaType;
    public $mediaId;

    public function __construct($mediaType, $mediaId)
    {
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
