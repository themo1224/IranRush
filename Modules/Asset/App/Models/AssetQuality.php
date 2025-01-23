<?php

namespace Modules\Asset\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Asset\Database\factories\AssetQualityFactory;

class AssetQuality extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    public function asset()
    {
        return $this->morphTo();
    }
}
