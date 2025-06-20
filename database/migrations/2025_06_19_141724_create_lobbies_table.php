<?php

use App\Enums\InvitePolicy;
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
        Schema::create('lobbies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('owner_id')->constrained(table: 'users', column: 'id', indexName: 'lobby_user_idx'); // Владелец
            $table->string('name');
            $table->string('join_code')->unique();
            $table->unsignedTinyInteger('max_members')->default(5);
            $table->string('invite_policy')->default(InvitePolicy::ownerOnly->value);
            $table->boolean('is_frozen')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lobbies');
    }
};
