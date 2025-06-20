<?php

use App\Enums\NpcType;
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
        Schema::create('game_npcs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_session_id')->constrained('game_sessions', 'id', 'game_npc_game_session_idx');
            // $table->foreignId('user_id')->constrained('users', 'id', 'game_npc_user_idx'); // Управляет ДМ, нет смысла
            $table->foreignId('prototype_npc_id')->constrained('npcs', 'id', 'game_npc_prototype_npc_idx');
            $table->string('name');
            $table->string('npc_type')->default(NpcType::Generic->value); // На всякий случай, но будет копироваться из прототипа
            $table->text('description')->nullable();
            $table->string('icon_path')->nullable(); // Иконка персонажа, например, в списках
            $table->string('token_icon_path')->nullable(); // Картинка токена
            $table->json('sheet')->nullable(); // Динамические данные

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_npcs');
    }
};
