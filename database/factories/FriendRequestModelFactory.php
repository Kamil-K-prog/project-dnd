<?php

namespace Database\Factories;

use App\Models\FriendRequestModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FriendRequestModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FriendRequestModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // По умолчанию, мы создаем ДВУХ НОВЫХ пользователей для каждого запроса.
            // Это самый "безопасный" вариант по умолчанию, который гарантирует,
            // что у нас всегда есть валидные ID.
            'sender_id' => User::factory(),
            'recipient_id' => User::factory(),

            // Статус по умолчанию - 'pending'
            'status' => \App\Enums\FriendRequestStatus::Pending,
        ];
    }
}
