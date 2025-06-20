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
        Schema::create('template_tokens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_template_id')->constrained(table: 'game_templates', column: 'id', indexName: 'template_token_game_template_idx')->cascadeOnDelete();
            $table->foreignId('map_id')->constrained('maps', 'id', 'template_token_map_idx')->cascadeOnDelete();
            $table->morphs('tokenable');
            $table->unsignedInteger('position_x')->default(0);
            $table->unsignedInteger('position_y')->default(0);
            $table->unsignedInteger('size_x')->default(1);
            $table->unsignedInteger('size_y')->default(1);
            $table->boolean('is_visible')->default(true);
            $table->json('instance_data')->nullable(); // Будет напрямую скопирован в game_items.data или game_npcs.sheet
            $table->json('display_data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_tokens');
    }
};
