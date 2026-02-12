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
        Schema::create('user_room_chat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from')->constrained('users', 'id');
            $table->foreignId('to')->constrained('users', 'id');
            $table->string('type');
            $table->boolean('isRead')->default(false)->nullable();
            $table->text('chat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_room_chats');
    }
};
