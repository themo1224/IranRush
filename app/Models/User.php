<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Auth\Database\factories\UserFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Model
{
    use HasFactory, HasApiTokens, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',             // User's first name
        'email',            // User's email address
        'phone_number',     // User's phone number (unique identifier)
        'password',         // User's hashed password
        'avatar',           // Profile picture URL or path
        'role',             // Role of the user (e.g., admin, user, seller)
        'status',           // Account status (e.g., active, inactive, suspended)
        'plan_id',          // If the user is on a subscription plan
        'plan_expires_at',  // Expiration date of the current subscription plan
        'bio',              // Short bio or description for the user
        'address',          // Physical address
        'city',             // User's city
        'country',          // User's country
        'zip_code',         // Postal/ZIP code
    ];
    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
