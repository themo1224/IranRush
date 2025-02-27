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
        Schema::create('tutorials', function (Blueprint $table) {
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('embed_aparat_url')->nullable();
            $table->text('text_area')->nullable(); // Fixed the typo from "text_erea" to "text_area"
            $table->string('title');
            $table->string('slug')->unique();
            

            // SEO fields (instead of JSON for better indexing and querying)
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->boolean('indexable')->default(true);

            // Connecting author to users table
            $table->unsignedBigInteger('media_id');
            // Image relation (assuming an 'images' table exists)
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutorials');
    }
};
