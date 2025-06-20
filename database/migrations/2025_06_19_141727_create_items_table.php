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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users', 'id', 'item_user_idx');
            $table->string('name');
            $table->string('item_type')->default(ItemType::Generic->value);
            $table->text('description')->nullable();
            $table->text('dm_notes')->nullable();
            $table->string('icon_path')->nullable(); // Иконка предмета, например, в списках
            $table->string('token_icon_path')->nullable(); // Картинка токена
            $table->json('data')->nullable(); // Динамические данные. Копируются в токен

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
