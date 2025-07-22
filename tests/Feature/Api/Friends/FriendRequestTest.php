<?php

namespace Tests\Feature\Api\Friends;

use App\Models\FriendRequestModel; // Убедись, что импорт правильный
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FriendRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_their_friend_requests(): void
    {
        // 1. Подготовка (Arrange)
        // Создаем двух конкретных пользователей, которые будут участниками теста
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        // Создаем входящий запрос, но теперь с помощью фабрики,
        // и мы ЯВНО указываем, какие пользователи должны быть отправителем и получателем.
        FriendRequestModel::factory()->create([
            'sender_id' => $anotherUser->id,
            'recipient_id' => $user->id,
        ]);

        // Создадим еще один, "посторонний" запрос, чтобы убедиться,
        // что наш API не возвращает лишнего.
        FriendRequestModel::factory()->create();

        // 2. Действие (Act)
        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/friends/requests');

        // 3. Проверка (Assert)
        $response->assertStatus(200);
        // Мы ожидаем только 1 запрос, а не 2
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.sender.id', $anotherUser->id);
        $response->assertJsonPath('data.0.recipient.id', $user->id);
    }

}
