<?php

namespace Modules\Asset\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Asset\App\Models\Asset;
use Illuminate\Support\Str;

class AssetService
{
    protected $disk;

    public function __construct()
    {
        $this->disk = 'liara'; // Name of the disk configured in `filesystems.php`
    }

    /**
     * Store the uploaded file in Liara bucket.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public function storeFile($file): array
    {
        try {
            // Generate unique file name and path
            $uniqueName = uniqid() . '-' . $file->getClientOriginalName();
            $filePath = 'user-assets/' . $uniqueName;

            // Upload to Liara storage
            $stored = Storage::disk($this->disk)->put($filePath, file_get_contents($file));

            if ($stored) {
                // Generate URL for the stored file
                $fileUrl = Storage::disk($this->disk)->url($filePath);

                return [
                    'success' => true,
                    'file_url' => $fileUrl,
                ];
            }

            return ['success' => false];
        } catch (\Exception $e) {
            Log::error('Error storing file: ' . $e->getMessage());
            return ['success' => false];
        }
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
