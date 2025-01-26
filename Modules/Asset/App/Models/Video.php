<?php

namespace Modules\Asset\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Asset\Database\factories\VideoFactory;

class Video extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'file_path',
        'price',
        'name',
        'resolution',
        'duration',
        'description',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
