<?php

use App\Enums\GameStatus;
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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lobby_id')->constrained('lobbies', 'id', 'game_session_lobby_idx');
            $table->foreignId('game_template_id')->constrained('game_templates', 'id', 'game_session_template_idx')->nullable();
            $table->string('name');
            $table->string('status')->default(GameStatus::Preparing->value);
            $table->boolean('is_ai_dm')->default(false);
            $table->foreignId('active_map_id')->constrained('maps', 'id', 'game_session_map_idx')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
