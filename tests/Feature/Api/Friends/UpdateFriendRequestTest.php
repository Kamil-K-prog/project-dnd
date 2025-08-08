<?php

namespace Tests\Feature\Api\Friends;

use App\Enums\FriendRequestStatus;
use App\Models\FriendRequestModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateFriendRequestTest extends TestCase
{
    use RefreshDatabase;

    private User $sender;
    private User $recipient;
    private FriendRequestModel $friendRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sender = User::factory()->create();
        $this->recipient = User::factory()->create();

        $this->friendRequest = FriendRequestModel::factory()->create([
            'sender_id' => $this->sender->id,
            'recipient_id' => $this->recipient->id,
            'status' => FriendRequestStatus::Pending,
        ]);
    }

    // --- Тесты для DeclineController ---

    public function test_recipient_can_decline_friend_request(): void
    {
        $response = $this->actingAs($this->recipient, 'sanctum')
            ->postJson('/api/friends/requests/' . $this->friendRequest->id . '/decline');

        $response->assertStatus(200);
        $response->assertJsonPath('data.status', FriendRequestStatus::Declined->value);

        $this->assertDatabaseHas('friend_requests', [
            'id' => $this->friendRequest->id,
            'status' => FriendRequestStatus::Declined->value,
        ]);
    }

    public function test_sender_cannot_decline_a_request(): void
    {
        $response = $this->actingAs($this->sender, 'sanctum')
            ->postJson('/api/friends/requests/' . $this->friendRequest->id . '/decline');

        $response->assertStatus(403);
    }

    // --- Тесты для DestroyController (Cancel) ---

    public function test_sender_can_cancel_their_own_request(): void
    {
        // Для этого эндпоинта мы используем метод DELETE
        $response = $this->actingAs($this->sender, 'sanctum')
            ->deleteJson('/api/friends/requests/' . $this->friendRequest->id);

        $response->assertStatus(204); // Ожидаем "No Content"

        $this->assertDatabaseHas('friend_requests', [
            'id' => $this->friendRequest->id,
            'status' => FriendRequestStatus::Cancelled->value,
        ]);
    }

    public function test_recipient_cannot_cancel_a_request_sent_to_them(): void
    {
        $response = $this->actingAs($this->recipient, 'sanctum')
            ->deleteJson('/api/friends/requests/' . $this->friendRequest->id);

        $response->assertStatus(403);
    }

    public function test_unrelated_user_cannot_cancel_or_decline_request(): void
    {
        $unrelatedUser = User::factory()->create();

        $declineResponse = $this->actingAs($unrelatedUser, 'sanctum')
            ->postJson('/api/friends/requests/' . $this->friendRequest->id . '/decline');

        $cancelResponse = $this->actingAs($unrelatedUser, 'sanctum')
            ->deleteJson('/api/friends/requests/' . $this->friendRequest->id);

        $declineResponse->assertStatus(403);
        $cancelResponse->assertStatus(403);
    }
}
