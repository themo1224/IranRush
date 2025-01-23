<?php

namespace Modules\Asset\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Asset\App\Models\Asset;
use Illuminate\Support\Str;

class VideoService
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
    public function storeFile($file, $userID): array
    {
        try {
            // Generate unique file name and path
            $uniqueName = uniqid() . '-' . $file->getClientOriginalName();
            $filePath = 'user-assets/' . '{$userId}/' . $uniqueName;

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
    public function createAsset(array $data): Asset
    {
        return Asset::create($data);
    }

    public function assignTagsAndCategories(Asset $asset, array $tags, int $categoryId): Asset
    {
        try {
            if (!empty($tags)) {
                $tagIds = Asset::whereIn('name', $tags)->pluck('id')->toArray();

                $asset->tags()->sync($tagIds);
            }

            $asset->category_id = $categoryId;
            $asset->save();
            return $asset;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
