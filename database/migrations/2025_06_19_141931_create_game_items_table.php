<?php

use App\Enums\ItemType;
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
        Schema::create('game_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_session_id')->constrained('game_sessions', 'id', 'game_item_game_session_idx');
            // $table->foreignId('user_id')->constrained('users', 'id', 'game_item_user_idx'); // Либо лежит в инвентаре, либо на земле, владелец не нужен
            $table->foreignId('prototype_item_id')->constrained('items', 'id', 'game_item_item_idx');
            $table->string('name');
            $table->string('item_type')->default(ItemType::Generic->value);
            $table->text('description')->nullable();
            $table->text('dm_notes')->nullable();
            $table->string('icon_path')->nullable();
            $table->string('token_icon_path')->nullable();
            $table->json('data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_items');
    }
};
