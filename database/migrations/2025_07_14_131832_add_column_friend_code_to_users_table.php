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
        Schema::table('users', function (Blueprint $table) {
            $table->string('friend_code')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Сначала удаляем уникальный индекс, ссылаясь на него по имени столбца
            $table->dropUnique(['friend_code']);
            // Затем удаляем сам столбец
            $table->dropColumn('friend_code');
        });
    }
};
