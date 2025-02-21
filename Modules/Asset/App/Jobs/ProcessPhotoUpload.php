<?php

namespace Modules\Asset\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Asset\App\Models\Photo;
use Modules\Asset\Services\PhotoService;


class ProcessPhotoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $photo;
    protected $file;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($file, $userId, Photo $photo)
    {
        $this->file = $file;
        $this->userId = $userId;
        $this->photo = $photo;
    }

    /**
     * Execute the job.
     */
    public function handle(PhotoService $photoService)
    {
         // Apply watermark to the file
         $photoService->applyWatermark($this->photo->file_path);

         // Store the watermarked version
         $photoService->storeWatermarkedVersion($this->photo->file_path, $this->photo);
 

    }
}
