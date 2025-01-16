<?php

namespace Modules\Asset\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Asset\App\Http\Requests\GenerateSignedUrlRequest;
use Modules\Auth\Services\AssetService;

class AssetController extends Controller
{
    protected AssetService  $assetService;
    public function __construct(AssetService  $assetService)
    {
        $this->assetService = $assetService;
    }

    public function generateSignedUrl(GenerateSignedUrlRequest $request)
    {
        $signedUrl = $this->assetService->generateSignedUrl($request->file_name);
        return response()->json(['signed_url' => $signedUrl], 200);
    }

    public function selectQuality(Request $request)
    {
        // Handle quality selection and pricing logic
    }
}
