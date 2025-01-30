<?php

namespace Modules\Asset\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Modules\Asset\App\Http\Enums\AssetStatus;
use Modules\Asset\App\Http\Requests\PhotoRequest;
use Modules\Asset\Services\PhotoService;
use Modules\Notification\App\Events\MediaUploadEvent;
use Modules\Notification\App\Notifications\AssetUploadedNotification;

class PhotoController extends Controller
{
    protected $photoService;
    public function __construct(PhotoService $photoService)
    {
        $this->photoService = $photoService;
    }
    public function uploadPhoto(PhotoRequest $request)
    {
        $userId = auth()->user()->id;
        $file = $request->file('upload_file');
        $price = $request->input('price');
        try {
            $uploadResult = $this->photoService->storeFile($file, $userId);
            if ($uploadResult['success']) {
                $photo = $this->savePhotoMetadata($uploadResult['file_url'], $file, $price, $userId);
                // Assign tags and category to the photo
                $tags = $request->input('tags', []);
                $categoryId = $request->input('category_id');
                $this->photoService->assignTagsAndCategories($photo, $tags, $categoryId);

                event(new MediaUploadEvent('photo', $photo->id));

                return response()->json([
                    'success' => true,
                    'message' => 'Photo uploaded successfully',
                    'file_url' => $uploadResult['file_url'],
                    'photo' => $photo,
                ], 200);
            }

            // Handle case where upload fails but doesn't throw an exception
            return response()->json([
                'success' => false,
                'message' => 'Photo upload failed unexpectedly',
            ], 500);
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error during photo upload: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during photo upload.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    private function savePhotoMetadata($fileUrl, $file, $price, $userId)
    {
        return $this->photoService->createPhoto([
            'user_id' => $userId,
            'file_path' => $fileUrl,
            'name' => $file->getClientOriginalName(),
            'price' => $price,
            'status' => AssetStatus::PENDING
        ]);
    }
}
