<?php

namespace Modules\Asset\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Modules\Asset\App\Http\Enums\AssetStatus;
use Modules\Asset\App\Http\Requests\VideoRequest;
use Modules\Asset\Services\VideoService;

class VideoController extends Controller
{
    protected $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function uploadVideo(VideoRequest $request)
    {

        $file = $request->file('upload_file');
        $price = $request->input('price');
        $userId = auth()->user()->id;

        try {
            // Handle file upload
            $uploadResult = $this->videoService->storeFile($file, $userId);

            if ($uploadResult['success']) {
                // Save metadata using a separate method
                $video = $this->saveVideoMetadata($uploadResult['file_url'], $file, $price, $userId, $request->description, $request->resolution);

                // Assign tags and category to the video
                $tags = $request->input('tags', []);
                $categoryId = $request->input('category_id');
                $this->videoService->assignTagsAndCategories($video, $tags, $categoryId);

                return response()->json([
                    'success' => true,
                    'message' => 'Video uploaded successfully',
                    'file_url' => $uploadResult['file_url'],
                    'video' => $video,
                ], 200);
            }

            // Handle case where upload fails but doesn't throw an exception
            return response()->json([
                'success' => false,
                'message' => 'Video upload failed unexpectedly',
            ], 500);
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error during video upload: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during video upload.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function saveVideoMetadata($fileUrl, $file, $price, $userId, $description, $Resolution)
    {
        return $this->videoService->createVideo([
            'user_id' => $userId,
            'file_path' => $fileUrl,
            'file_type' => $file->getClientMimeType(),
            'price' => $price,
            'name' => $file->getClientOriginalName(), // Get video name
            'resolution' => $Resolution, // Get video resolution
            'description' => $description, // Optional description
            'status' => AssetStatus::PENDING, // Set status to PENDING
        ]);
    }
}
