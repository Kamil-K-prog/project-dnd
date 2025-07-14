<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('friend_users', function (Blueprint $table) {
            // Таблица будет содержать сразу две записи при добавлении новой дружбы: прямую (User1 -> User2) и обратную (User2 -> User1)
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('friend_id')->constrained('users')->cascadeOnDelete();

            $table->unique(['user_id', 'friend_id'], 'user_user_idx'); // Составной уникальный индекс

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friend_users');
    }
};
