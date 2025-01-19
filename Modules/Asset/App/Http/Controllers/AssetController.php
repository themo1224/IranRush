<?php

namespace Modules\Asset\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Asset\App\Http\Requests\GenerateSignedUrlRequest;
use Modules\Asset\Services\AssetService;

class AssetController extends Controller
{
    protected $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'upload_file' => 'required|file|max:5000|mimes:jpeg,png,jpg,mp4,mp3', // Adjust file size and types as needed
        ]);

        $file = $request->file('upload_file');

        $uploadResult = $this->assetService->storeFile($file);

        if ($uploadResult['success']) {
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'file_url' => $uploadResult['file_url'],
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'File upload failed',
        ], 500);
    }

    public function saveAssetMetadata(Request $request)
    {
        $validatedData = $request->validate([
            'file_path' => 'required|string',
            'file_type' => 'required|string',
            'price' => 'nullable|numeric',
        ]);

        $validatedData['user_id'] = auth()->id();
        $asset = $this->assetService->storeAssetMetadata($validatedData);

        return response()->json([
            'message' => 'Asset metadata saved successfully.',
            'asset' => $asset,
        ], 201);
    }

    public function selectQuality(Request $request)
    {
        // Handle quality selection and pricing logic
    }
}
