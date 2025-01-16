<?php

namespace Modules\Asset\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Asset\Database\factories\AssetFactory;

class Asset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): AssetFactory
    {
        //return AssetFactory::new();
    }
}
