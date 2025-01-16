<?php

namespace Modules\Asset\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Asset\Database\factories\ProcessedAssetFactory;

class ProcessedAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'quality',
        'price',
        'file_path'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
