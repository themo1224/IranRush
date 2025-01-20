<?php

namespace Modules\Asset\App\Http\Controllers;

use Modules\Asset\App\Http\Enums\AssetStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Asset\App\Http\Requests\GenerateSignedUrlRequest;
use Modules\Asset\Services\AssetService;
use Exception;


class AssetController extends Controller
{
    protected $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    public function uploadFile(GenerateSignedUrlRequest $request)
    {
        $validatedData = $request->validated(); // This should throw an exception if validation fails
        $file = $request->file('upload_file');
        $price = $request->input('price');
        $userId = auth()->user()->id;
        try {
            // Handle file upload
            $uploadResult = $this->assetService->storeFile($file, $userId);

            if ($uploadResult['success']) {
                // Save metadata using a separate method
                $asset = $this->saveAssetMetadata($uploadResult['file_url'], $file, $price, $userId);

                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'file_url' => $uploadResult['file_url'],
                    'asset' => $asset,
                ], 200);
            }

            // Handle case where upload fails but doesn't throw an exception
            return response()->json([
                'success' => false,
                'message' => 'File upload failed unexpectedly',
            ], 500);
        } catch (Exception $e) {
            // Log the exception for debugging
            Log::error('Error during file upload: ' . $e->getMessage());

            // Return a structured error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during file upload.',
                'error' => $e->getMessage(), // For debugging, you might remove this in production
                'trace' => $e->getTraceAsString(), // Optional: Add for detailed debugging
            ], 500);
        }
    }
    /**
     * Save asset metadata to the database.
     *
     * @param string $fileUrl
     * @param \Illuminate\Http\UploadedFile $file
     * @param float $price
     * @param int $userId
     * @return \App\Models\Asset
     */

    private function saveAssetMetadata($fileUrl, $file, $price, $userId)
    {
        return $this->assetService->createAsset([
            'user_id' => $userId,
            'file_path' => $fileUrl,
            'file_type' => $file->getClientMimeType(),
            'price' => $price,
        ]);
    }
    public function selectQuality(Request $request)
    {
        // Handle quality selection and pricing logic
    }
}
