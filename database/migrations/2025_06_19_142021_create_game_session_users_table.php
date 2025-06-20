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
        Schema::create('game_session_users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_session_id')->constrained('game_sessions', 'id', 'game_session_user_game_session_idx');
            $table->foreignId('user_id')->constrained('users', 'id', 'game_session_user_user_idx');
            $table->foreignId('game_character_id')->constrained('game_characters', 'id', 'game_session_user_character_idx')->nullable();
            $table->string('role'); // Кастовать как Enum GameSessionRole

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_session_users');
    }
};
