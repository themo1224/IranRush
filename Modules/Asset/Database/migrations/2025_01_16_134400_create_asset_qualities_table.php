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
        Schema::create('asset_qualities', function (Blueprint $table) {
            $table->id();
            $table->morphs('asset');
            $table->string('quality'); // e.g., "4K", "1080p", "720p"
            $table->decimal('price', 10, 2); // Price for this specific quality
            $table->string('file_path')->nullable(); // Path for the processed quality
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_qualities');
    }
};
