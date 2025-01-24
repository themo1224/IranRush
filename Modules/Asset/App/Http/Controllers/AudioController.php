<?php

namespace Modules\Asset\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Modules\Asset\App\Http\Enums\AssetStatus;
use Modules\Asset\Services\AudioService;

class AudioController extends Controller
{
    protected $audioService;

    public function __construct(AudioService $audioService)
    {
        $this->audioService = $audioService;
    }

    public function uploadAudio(Request $request)
    {
        $request->validate([
            'upload_file' => 'required|mimetypes:audio/mpeg,audio/mp3|max:51200', // Limit to 50MB
            'price' => 'required|numeric|min:0',
            'tags' => 'array',
            'tags.*' => 'string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:500',
        ]);

        $file = $request->file('upload_file');
        $price = $request->input('price');
        $userId = auth()->user()->id;

        try {
            // Handle file upload
            $uploadResult = $this->audioService->storeFile($file, $userId);

            if ($uploadResult['success']) {
                // Save metadata using a separate method
                $audio = $this->saveAudioMetadata(
                    $uploadResult['file_url'],
                    $file,
                    $price,
                    $userId,
                    $request->input('description'),
                    $request->input('duration')
                );

                // Assign tags and category to the audio
                $tags = $request->input('tags', []);
                $categoryId = $request->input('category_id');
                $this->audioService->assignTagsAndCategories($audio, $tags, $categoryId);

                return response()->json([
                    'success' => true,
                    'message' => 'Audio uploaded successfully',
                    'file_url' => $uploadResult['file_url'],
                    'audio' => $audio,
                ], 200);
            }

            // Handle case where upload fails but doesn't throw an exception
            return response()->json([
                'success' => false,
                'message' => 'Audio upload failed unexpectedly',
            ], 500);
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error during audio upload: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during audio upload.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function saveAudioMetadata($fileUrl, $file, $price, $userId, $description, $duration)
    {
        return $this->audioService->createAudio([
            'user_id' => $userId,
            'file_path' => $fileUrl,
            'file_type' => $file->getClientMimeType(),
            'price' => $price,
            'name' => $file->getClientOriginalName(), // Get audio name
            'duration' => $duration, // Audio duration in seconds
            'description' => $description, // Optional description
            'status' => AssetStatus::PENDING, // Set status to PENDING
        ]);
    }
}
