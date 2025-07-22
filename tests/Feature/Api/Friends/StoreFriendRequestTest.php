<?php

namespace Tests\Feature\Api\Friends;

use App\Models\FriendRequestModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreFriendRequestTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $anotherUser;

    /**
     * Подготовка данных перед каждым тестом
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->anotherUser = User::factory()->create();
    }

    /**
     * Основной тест на успешное создание запроса
     */
    public function test_can_send_friend_request(): void
    {
        // Действие: отправляем API запрос от имени $this->user
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/friends/requests', [
                'friend_code' => $this->anotherUser->friend_code,
            ]);

        // Проверки
        $response->assertStatus(201); // 201 Created - стандарт для успешного POST запроса
        $response->assertJsonStructure([ // Проверяем, что ответ имеет нужную структуру
            'data' => [
                'id',
                'status',
                'sender' => ['id', 'name'],
                'recipient' => ['id', 'name'],
            ]
        ]);
        $response->assertJsonPath('data.sender.id', $this->user->id);
        $response->assertJsonPath('data.recipient.id', $this->anotherUser->id);

        // Проверяем, что запись действительно появилась в БД
        $this->assertDatabaseHas('friend_requests', [
            'sender_id' => $this->user->id,
            'recipient_id' => $this->anotherUser->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Тест на валидацию: нельзя отправить запрос самому себе
     */
    public function test_cannot_send_friend_request_to_self(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/friends/requests', [
                'friend_code' => $this->user->friend_code, // Пытаемся добавить себя
            ]);

        // Проверки
        $response->assertStatus(422); // 422 Unprocessable Entity - стандарт для ошибок валидации
        $response->assertJsonValidationErrors('friend_code'); // Проверяем, что ошибка именно в поле friend_code
    }

    /**
     * Тест на валидацию: нельзя отправить запрос тому, с кем уже дружишь
     */
    public function test_cannot_send_request_if_already_friends(): void
    {
        // Сначала делаем пользователей друзьями
        $this->user->friends()->attach($this->anotherUser->id);
        $this->anotherUser->friends()->attach($this->user->id);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/friends/requests', [
                'friend_code' => $this->anotherUser->friend_code,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('friend_code');
    }

    /**
     * Тест на валидацию: нельзя отправить запрос, если уже есть активный
     */
    public function test_cannot_send_request_if_pending_request_exists(): void
    {
        // Создаем существующий запрос
        FriendRequestModel::factory()->create([
            'sender_id' => $this->user->id,
            'recipient_id' => $this->anotherUser->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/friends/requests', [
                'friend_code' => $this->anotherUser->friend_code,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('friend_code');
    }

    /**
     * Тест на валидацию: неверный код дружбы
     */
    public function test_cannot_send_request_with_invalid_friend_code(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/friends/requests', [
                'friend_code' => 'INVALIDCODE',
            ]);

        $response->assertStatus(422);
        // Здесь сработает сразу несколько правил: size:8 и exists:users
        $response->assertJsonValidationErrors('friend_code');
    }
}
