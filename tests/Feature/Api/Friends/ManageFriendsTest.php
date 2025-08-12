<?php

namespace Tests\Feature\Api\Friends;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageFriendsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $friend;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->friend = User::factory()->create();

        // Сразу делаем их друзьями для тестов (двусторонняя связь)
        $this->user->friends()->attach($this->friend->id);
        $this->friend->friends()->attach($this->user->id);
    }

    // --- Тесты для IndexController (Получение списка друзей) ---

    public function test_can_get_list_of_friends(): void
    {
        // Создадим еще одного "не друга"
        User::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/friends');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data'); // Убеждаемся, что в списке только 1 друг
        $response->assertJsonPath('data.0.id', $this->friend->id);
    }

    public function test_guest_cannot_get_list_of_friends(): void
    {
        $response = $this->getJson('/api/friends');

        $response->assertStatus(401); // Unauthorized
    }

    public function test_can_get_empty_list_of_friends(): void
    {
        // У пользователя из setUp есть один друг, создадим нового без друзей
        $userWithNoFriends = User::factory()->create();

        $response = $this->actingAs($userWithNoFriends, 'sanctum')->getJson('/api/friends');

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    public function test_friends_list_is_paginated(): void
    {
        // Создадим 55 друзей для пользователя
        $friends = User::factory()->count(55)->create();
        $this->user->friends()->attach($friends->pluck('id'));

        // Не забываем, что у пользователя уже есть один друг из setUp
        // Итого 56 друзей

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/friends');

        $response->assertStatus(200);
        $response->assertJsonCount(50, 'data'); // На первой странице должно быть 50
        $response->assertJsonStructure([
            'data',
            'links',
            'meta' => [
                'current_page',
                'last_page',
                'per_page',
                'total'
            ]
        ]);
        $this->assertEquals(56, $response->json('meta.total'));
    }

    // --- Тесты для DestroyController (Удаление друга) ---

    public function test_can_remove_a_friend(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/friends/' . $this->friend->id);

        $response->assertStatus(204); // Ожидаем "No Content"

        // Проверяем, что обе записи об их дружбе удалены
        $this->assertDatabaseMissing('friend_users', [
            'user_id' => $this->user->id,
            'friend_id' => $this->friend->id,
        ]);
        $this->assertDatabaseMissing('friend_users', [
            'user_id' => $this->friend->id,
            'friend_id' => $this->user->id,
        ]);
    }

    public function test_cannot_remove_a_user_who_is_not_a_friend(): void
    {
        $notAFriend = User::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/friends/' . $notAFriend->id);

        // Ожидаем ошибку авторизации из Form Request
        $response->assertStatus(403);
    }

    public function test_guest_cannot_remove_a_friend(): void
    {
        $response = $this->deleteJson('/api/friends/' . $this->friend->id);

        $response->assertStatus(401);
    }

    public function test_cannot_remove_non_existent_user(): void
    {
        $nonExistentUserId = 9999;

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/friends/' . $nonExistentUserId);

        $response->assertStatus(404);
    }

    // --- Тесты для RegenerateFriendCodeController ---

    public function test_can_regenerate_friend_code(): void
    {
        $oldCode = $this->user->friend_code;

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/user/regenerate-friend-code');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['friend_code']]);

        // Проверяем, что код в ответе не совпадает со старым
        $this->assertNotEquals($oldCode, $response->json('data.friend_code'));

        // Проверяем, что код в базе данных действительно обновился
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'friend_code' => $response->json('data.friend_code'),
        ]);
    }
}
