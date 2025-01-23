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
        // Photos Table
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Asset owner
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null'); // Category association
            $table->string('name');
            $table->integer('price')->nullable();
            $table->string('file_path'); // Path to the file
            $table->integer('width')->nullable(); // Width in pixels
            $table->integer('height')->nullable(); // Height in pixels
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();
        });

        // Videos Table
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Asset owner
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null'); // Category association
            $table->string('name');
            $table->integer('price')->nullable();
            $table->string('file_path'); // Path to the file
            $table->integer('duration')->nullable(); // Duration in seconds
            $table->string('resolution')->nullable(); // Resolution (e.g., 1080p, 720p)
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();
        });

        // Audios Table
        Schema::create('audios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Asset owner
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null'); // Category association
            $table->string('name');
            $table->integer('price')->nullable();
            $table->string('file_path'); // Path to the file
            $table->integer('duration')->nullable(); // Duration in seconds
            $table->integer('bitrate')->nullable(); // Bitrate in kbps
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('audios');
    }
};
