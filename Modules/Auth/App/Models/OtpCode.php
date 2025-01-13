<?php

namespace Modules\Auth\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Database\factories\OtpCodeFactory;

class OtpCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['phone_number', 'code', 'expires_at'];

    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }
}
