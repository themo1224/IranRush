<?php

namespace Modules\Asset\Services;

use App\Models\Tag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Asset\App\Models\Asset;
use Illuminate\Support\Str;
use Modules\Asset\App\Models\Audio;

class AudioService
{
    protected $disk;

    public function __construct()
    {
        $this->disk = 'liara'; // Name of the disk configured in `filesystems.php`
    }

    /**
     * Store the uploaded audio in Liara bucket.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public function storeFile($file, $userId): array
    {
        try {
            // Generate unique file name and path
            $uniqueName = uniqid() . '-' . $file->getClientOriginalName();
            $filePath = 'user-audios/' . $userId . '/' . $uniqueName;

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
            Log::error('Error storing audio: ' . $e->getMessage());
            return ['success' => false];
        }
    }

    /**
     * Create a new audio record in the database.
     */
    public function createAudio(array $data): Audio
    {
        return Audio::create($data);
    }

    /**
     * Assign tags and category to the audio.
     */
    public function assignTagsAndCategories(Audio $audio, array $tags, int $categoryId = null): Audio
    {
        try {
            // Assign tags
            if (!empty($tags)) {
                $tagIds = Tag::whereIn('name', $tags)->pluck('id')->toArray();

                foreach ($tags as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }

                $audio->tags()->sync($tagIds);
            }

            // Assign category
            $audio->category_id = $categoryId;
            $audio->save();

            return $audio;
        } catch (\Exception $e) {
            Log::error('Error assigning tags and category to audio: ' . $e->getMessage());
            throw $e;
        }
    }
}
