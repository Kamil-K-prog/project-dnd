<?php

namespace Tests\Feature\Api\Friends;

use App\Enums\FriendRequestStatus;
use App\Models\FriendRequestModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcceptFriendRequestTest extends TestCase
{
    use RefreshDatabase;

    private User $sender;
    private User $recipient;
    private FriendRequestModel $friendRequest;

    /**
     * Подготовка данных перед каждым тестом
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->sender = User::factory()->create();
        $this->recipient = User::factory()->create();

        // Создаем "висящий" запрос от отправителя к получателю
        $this->friendRequest = FriendRequestModel::factory()->create([
            'sender_id' => $this->sender->id,
            'recipient_id' => $this->recipient->id,
            'status' => FriendRequestStatus::Pending,
        ]);
    }

    /**
     * Тест на успешное принятие запроса
     */
    public function test_recipient_can_accept_friend_request(): void
    {
        // Действие: Получатель ($recipient) принимает запрос
        $response = $this->actingAs($this->recipient, 'sanctum')
            ->postJson('/api/friends/requests/' . $this->friendRequest->id . '/accept');

        // Проверка ответа API
        $response->assertStatus(200); // Ожидаем успешный ответ
        $response->assertJsonPath('data.status', FriendRequestStatus::Accepted->value);
        $response->assertJsonPath('data.id', $this->friendRequest->id);

        // Проверка изменений в БД
        $this->assertDatabaseHas('friend_requests', [
            'id' => $this->friendRequest->id,
            'status' => FriendRequestStatus::Accepted->value,
        ]);

        // Проверяем, что создалась прямая связь в сводной таблице
        $this->assertDatabaseHas('friend_users', [
            'user_id' => $this->sender->id,
            'friend_id' => $this->recipient->id,
        ]);

        // Проверяем, что создалась и обратная связь
        $this->assertDatabaseHas('friend_users', [
            'user_id' => $this->recipient->id,
            'friend_id' => $this->sender->id,
        ]);
    }

    /**
     * Тест на ошибку: отправитель не может сам принять свой запрос
     */
    public function test_sender_cannot_accept_their_own_request(): void
    {
        // Действие: Отправитель ($sender) пытается принять свой же запрос
        $response = $this->actingAs($this->sender, 'sanctum')
            ->postJson('/api/friends/requests/' . $this->friendRequest->id . '/accept');

        // Проверка: Ожидаем ошибку 403 Forbidden, так как это провал авторизации в Form Request
        $response->assertStatus(403);

        // Проверяем, что в БД ничего не изменилось
        $this->assertDatabaseHas('friend_requests', [
            'id' => $this->friendRequest->id,
            'status' => FriendRequestStatus::Pending->value, // Статус остался 'pending'
        ]);
        $this->assertDatabaseMissing('friend_users', [
            'user_id' => $this->sender->id,
            'friend_id' => $this->recipient->id,
        ]);
    }

    /**
     * Тест на ошибку: посторонний пользователь не может принять чужой запрос
     */
    public function test_unrelated_user_cannot_accept_friend_request(): void
    {
        // Создаем постороннего пользователя
        $unrelatedUser = User::factory()->create();

        // Действие: Посторонний пользователь пытается принять чужой запрос
        $response = $this->actingAs($unrelatedUser, 'sanctum')
            ->postJson('/api/friends/requests/' . $this->friendRequest->id . '/accept');

        // Проверка: Ожидаем ошибку 403 Forbidden
        $response->assertStatus(403);
    }

    /**
     * Тест на ошибку: нельзя принять уже принятый запрос
     */
    public function test_cannot_accept_an_already_accepted_request(): void
    {
        // Сначала принимаем запрос, чтобы изменить его статус
        $this->friendRequest->update(['status' => FriendRequestStatus::Accepted]);

        // Действие: Пытаемся принять его снова
        $response = $this->actingAs($this->recipient, 'sanctum')
            ->postJson('/api/friends/requests/' . $this->friendRequest->id . '/accept');

        // Здесь можно ожидать либо 403, либо 422, в зависимости от того,
        // добавишь ли ты проверку статуса в authorize() или rules().
        // Давай предположим, что authorize() должен пропускать, а логика - нет.
        // Поэтому для начала можно оставить проверку на 403, а потом уточнить.
        // Для чистоты, лучше добавить правило в Form Request, чтобы возвращало 422.
        // Но сейчас 403 от `authorize` тоже будет корректным поведением.
        // Добавим в authorize `&& $friendRequest->status === FriendRequestStatus::Pending`
        // Тогда тест должен вернуть 403
        $response->assertStatus(403);
    }
}
