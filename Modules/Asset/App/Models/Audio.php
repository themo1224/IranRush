<?php

namespace Modules\Asset\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Asset\Database\factories\AudioFactory;

class Audio extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "audios";
    protected $fillable = [
        'user_id',
        'file_path',
        'price',
        'name',
        'duration',    // Specific to audios
        'description', // Optional
        'category_id',
        'status',
    ];

    public function qualities()
    {
        return $this->morphMany(AssetQuality::class, 'asset');
    }

    public function processedVersions()
    {
        return $this->morphMany(ProcessedAsset::class, 'asset');
    }
}
