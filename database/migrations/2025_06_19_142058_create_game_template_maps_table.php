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
        Schema::create('game_template_maps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_template_id')->constrained('game_templates', 'id', 'game_template_map_game_template_idx');
            $table->foreignId('map_id')->constrained('maps', 'id', 'game_template_map_map_idx');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_template_maps');
    }
};
