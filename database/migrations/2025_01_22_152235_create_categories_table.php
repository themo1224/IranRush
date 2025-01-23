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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Category name
            $table->string('slug')->unique(); // Unique slug
            $table->text('description')->nullable(); // Description (optional)
            $table->string('meta_title')->nullable(); // Meta title
            $table->text('meta_description')->nullable(); // Meta description
            $table->string('meta_keywords')->nullable(); // Meta keywords
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete(); // Parent category (self-referencing)
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete(); // Media relationship
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
