<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Asset\App\Models\Asset;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function taggedAssets()
    {
        return $this->morphedByMany(Asset::class, 'taggable');
    }

    // public function taggedBlogs()
    // {
    //     return $this->morphedByMany(Blog::class, 'taggable');
    // }
}
