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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users', 'id', 'character_user_idx'); // Создатель. Если в дальнейшем будет функция "Поделиться", то будет копия записи, но с другим ID
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon_path')->nullable(); // Иконка персонажа, например, в списках
            $table->string('token_icon_path')->nullable(); // Картинка токена
            $table->json('character_sheet')->nullable(); // Динамические данные

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
