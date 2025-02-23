<?php

namespace Modules\Asset\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Asset\App\Models\Photo;
use Modules\Asset\Services\PhotoService;
use Illuminate\Support\Facades\Storage;



class ProcessPhotoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $photo;
    protected $filePath;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(Photo $photo, $filePath, $userId )
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
        $this->photo = $photo;
    }

    /**
     * Execute the job.
     */
    public function handle(PhotoService $photoService)
    {
        // Pass the file path directly instead of retrieving the file content
        $photoService->storeWatermarkedVersion($this->filePath, $this->photo);
    }
}
