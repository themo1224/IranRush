<?php

namespace Modules\Asset\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Asset\App\Http\Requests\GenerateSignedUrlRequest;
use Modules\Asset\Services\AssetService;

class AssetController extends Controller
{
    protected AssetService  $assetService;
    public function __construct(AssetService  $assetService)
    {
        $this->assetService = $assetService;
    }

    public function generateSignedUrl(GenerateSignedUrlRequest $request)
    {
        $userId = Auth::user()->id;
        $signedUrl = $this->assetService->generateSignedUrl(
            $request->fileName,
            $request->fileType,
            $userId
        );
        return response()->json(['signed_url' => $signedUrl], 200);
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
