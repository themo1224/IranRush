<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // User's name
            $table->string('email')->unique()->nullable(); // User's email (nullable for OTP-only users)
            $table->string('phone_number')->unique(); // Phone number for OTP
            $table->string('password')->nullable(); // Password for non-OTP users
            $table->boolean('is_seller')->default(false); // Can sell photos
            $table->boolean('is_professional')->default(false); // "حرفه ای شو" profile
            $table->timestamp('subscription_expires_at')->nullable(); // Subscription expiration
            $table->string('profile_photo_path')->nullable(); // Profile photo
            $table->rememberToken(); // For "Remember Me" sessions
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
