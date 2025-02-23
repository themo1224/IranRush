<?php

namespace Modules\Asset\Services;

use App\Models\Tag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
// use Modules\Asset\App\Models\Asset;
// use Illuminate\Support\Str;
use Modules\Asset\App\Models\Photo;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;



class PhotoService
{
    protected $disk;
    protected $imageManager;


    public function __construct()
    {
        $this->disk = 'liara'; // Name of the disk configured in `filesystems.php`
        $this->imageManager = new ImageManager(new Driver()); // Create new Intervention Image Manager

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
                    'file_path' => $filePath,
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
        return Photo::create($data)->fresh();
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

    public function applyWatermark($filePath)
    {
        try {
            $fileContent= Storage::disk($this->disk)->get($filePath);
            // Read the image using ImageManager
            $image = $this->imageManager->read($fileContent);
            dd($image); 
            // Load watermark
            $watermarkPath = public_path('watermark.png');
            

            if (!file_exists($watermarkPath)) {
                throw new \Exception("Watermark file not found: $watermarkPath");
            }

            // Read watermark image
            $watermark = $this->imageManager->read(file_get_contents($watermarkPath));

            // Resize watermark to 30% of image width
            $watermark->scale(width: $image->width() * 0.3);

            // Insert watermark at bottom-right corner
            $image->place($watermark, 'bottom-right', 10, 10);
            // Encode the image
            return $image->encode();
            
        } catch (\Exception $e) {
            Log::error('Error applying watermark: ' . $e->getMessage());
            return null;
        }
    }

    public function storeWaterMarkedVersion($filePath, Photo $photo)
    {
        try{
            $watermarkedImage= $this->applyWatermark($filePath);
            if($watermarkedImage){
                $watermarkedPath= 'watermarked/' . $photo->user_id . '/' . basename($filePath);
 
                Storage::disk($this->disk)->put($watermarkedPath, (string) $watermarkedImage);

                $photo->watermarked_path= $watermarkedPath;
                $photo->save();

            }

        }catch(\Exception $e ){

        }
    }
}
