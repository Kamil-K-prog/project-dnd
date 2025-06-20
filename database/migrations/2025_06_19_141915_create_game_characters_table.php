<?php

use App\Enums\CharacterStatus;
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
        Schema::create('game_characters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_session_id')->constrained('game_sessions', 'id', 'game_character_game_session_idx');
            $table->foreignId('user_id')->constrained('users', 'id', 'game_character_user_idx');
            $table->foreignId('prototype_character_id')->constrained('characters', 'id', 'game_character_prototype_character_idx');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon_path')->nullable(); // Иконка персонажа, например, в списках
            $table->string('token_icon_path')->nullable(); // Картинка токена
            $table->json('character_sheet')->nullable(); // Динамические данные
            // position_x, position_y не нужны, они есть в таблице tokens
            $table->string('status')->default(CharacterStatus::Alive->value); // Вместо is_alive и is_observer. Enum: Alive/Dead/Observer/PlayingByAi
            $table->unsignedTinyInteger('moves_by_ai')->default(0); // На будущее - счётчик последовательных ходов, когда управление берёт ИИ
            $table->unsignedTinyInteger('moves_by_ai_total')->default(0); // Счётчик всех ходов за текущую игру ИИ

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_characters');
    }
};
