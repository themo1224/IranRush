<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HandlesMedia
{
    public function handleMedia($model, $mediaFiles, $dirName)
    {
        if (isset($mediaFiles) && is_array($mediaFiles)) {
            foreach ($mediaFiles as $media) {
                $path = Storage::putFile($dirName, $media);
                $model->media()->create([
                    'path' => $path,
                    'type' => $media->getClientMimeType(),
                ]);
            }
        }
    }
}