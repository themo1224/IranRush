<?php

namespace Modules\Asset\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Asset\Database\factories\PhotoFactory;

class Photo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'user_id',
        'file_path',
        'price',
        'category_id',
        'description',
        'width',
        'height',
        'status',
        'watermarked_path',
    ];

    public function qualities()
    {
        return $this->morphMany(AssetQuality::class, 'asset');
    }

    public function processedVersions()
    {
        return $this->morphMany(ProcessedAsset::class, 'asset');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
