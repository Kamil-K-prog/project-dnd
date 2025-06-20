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
        Schema::create('npcs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users', 'id', 'npc_user_idx');
            $table->string('name');
            $table->string('npc_type')->default(NpcType::Generic->value);
            $table->text('description')->nullable();
            $table->string('icon_path')->nullable(); // Иконка персонажа, например, в списках
            $table->string('token_icon_path')->nullable(); // Картинка токена
            $table->json('sheet')->nullable(); // Динамические данные.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('npcs');
    }
};
