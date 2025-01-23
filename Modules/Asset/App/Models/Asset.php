<?php

namespace Modules\Asset\App\Models;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Asset\Database\factories\AssetFactory;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_path',
        'file_type',
        'price',
        'status',
        'rejection_reason'
    ];

    public function processedAssets()
    {
        return $this->hasMany(ProcessedAsset::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
