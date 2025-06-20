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
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_session_id')->constrained(table: 'game_sessions', column: 'id', indexName: 'token_game_session_idx')->cascadeOnDelete(); // Правила наименования индексов в этом проекте: "{исходная таблица в ед. числе}_{таблица назначения в ед. числе}_idx"
            $table->foreignId('map_id')->constrained('maps', 'id', 'token_map_idx')->cascadeOnDelete();
            $table->morphs('tokenable');
            $table->unsignedInteger('position_x')->default(0); // То есть при создании фишки она буде спавниться на нулевых координатах
            $table->unsignedInteger('position_y')->default(0);
            $table->unsignedInteger('size_x')->default(1);
            $table->unsignedInteger('size_y')->default(1);
            $table->boolean('is_visible')->default(true);
            $table->json('display_data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
