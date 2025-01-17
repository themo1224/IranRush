<?php

namespace Modules\Asset\Services;

use Illuminate\Support\Facades\Storage;
use Modules\Asset\App\Models\Asset;
use Illuminate\Support\Str;

class AssetService
{
    public function generateSignedUrl(string $fileName, $fileType, int $userId)
    {
        // Generate unique file name with extension


        // Generate file path
        $filePath = "user-assets/{$userId}/original/{$fileName}";
        // Create signed URL
        $signedUrl = Storage::disk('liara')->temporaryUrl(
            $filePath,
            now()->addMinutes(60),
            ['Content-Type' => $fileType]
        );

        return [
            'signed_url' => $signedUrl,
            'file_path' => $filePath,
        ];
    }

    public function storeAssetMetadata(array $data): Asset
    {
        return Asset::create([
            'user_id' => $data['user_id'],
            'file_path' => $data['file_path'],
            'file_type' => $data['file_type'],
            'price' => $data['price'] ?? null,
            'status' => 'pending',
        ]);
    }
}
