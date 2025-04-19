<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HandlesMedia
{
    public function handleMedia($model, $mediaFiles, $dirName)
    {
        if (isset($mediaFiles) && is_array($mediaFiles)) {
            // Create a unique directory for each ticket
            $ticketDir = $dirName . '/' . $model->id;
            
            foreach ($mediaFiles as $media) {
                // Store the file in the ticket-specific directory
                $path = Storage::putFile($ticketDir, $media);
                $model->media()->create([
                    'path' => $path,
                    'type' => $media->getClientMimeType(),
                ]);
            }
        }
    }
}