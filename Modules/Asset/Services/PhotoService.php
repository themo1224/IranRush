<?php

namespace Modules\Asset\Services;

use App\Models\Tag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Asset\App\Models\Asset;
use Illuminate\Support\Str;
use Modules\Asset\App\Models\Photo;

class PhotoService
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
    public function storeFile($file, $userId): array
    {
        try {
            // Generate unique file name and path
            $uniqueName = uniqid() . '-' . $file->getClientOriginalName();
            $filePath = 'user-photos/' . $userId . '/' . $uniqueName;

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
            Log::error('Error storing photo: ' . $e->getMessage());
            return ['success' => false];
        }
    }

    /**
     * Create a new photo record in the database.
     */
    public function createPhoto(array $data): Photo
    {
        return Photo::create($data);
    }

    /**
     * Assign tags and category to the photo.
     */
    public function assignTagsAndCategories(Photo $photo, array $tags, int $categoryId = null): Photo
    {
        try {
            // Assign tags
            if (!empty($tags)) {
                $tagIds = Tag::whereIn('name', $tags)->pluck('id')->toArray();

                $photo->tags()->sync($tagIds);
            }

            // Assign category
            $photo->category_id = $categoryId;
            $photo->save();

            return $photo;
        } catch (\Exception $e) {
            Log::error('Error assigning tags and category to photo: ' . $e->getMessage());
            throw $e;
        }
    }
}
