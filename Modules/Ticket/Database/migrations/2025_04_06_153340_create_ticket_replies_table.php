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
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('message');
            $table->foreignId('media_id')->nullable()->constrained('media')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_replies', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);   // Drop the foreign key on admin_id
            $table->dropForeign(['media_id']);   // Drop the foreign key on media_id
            $table->dropForeign(['ticket_id']);   // Drop the foreign key on media_id
        });
        
        Schema::dropIfExists('ticket_replies');
    }
};
