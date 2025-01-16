<?php

namespace Modules\Asset\App\Models;

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
}
