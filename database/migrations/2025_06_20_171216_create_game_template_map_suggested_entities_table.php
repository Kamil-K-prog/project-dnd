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
        Schema::create('game_template_map_suggested_entities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_template_id')->constrained()->cascadeOnDelete();
            $table->foreignId('map_id')->constrained()->cascadeOnDelete(); // Карта, к которой относится это предложение

            // Полиморфная связь с прототипами Npc или Item
            $table->morphs('suggestable');

            $table->text('notes')->nullable(); // Заметки для ДМа (например, "Этот гоблин охраняет рычаг")

            $table->timestamps();

            // Уникальный индекс, чтобы одна и та же сущность не предлагалась дважды для одной карты в одном шаблоне
            $table->unique(['game_template_id', 'map_id', 'suggestable_id', 'suggestable_type'], 'unique_suggestion_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_template_map_suggested_entities');
    }
};
