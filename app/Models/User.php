<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\FriendRequestStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'friend_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * Возвращает список всех друзей конкретного пользователя
     *
     * @return BelongsToMany
     */
    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'friend_users', 'user_id', 'friend_id');
    }


    // Входящие запросы (где я - получатель)
    public function incomingFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequestModel::class, 'recipient_id');
    }

    // Исходящие запросы (где я - отправитель)
    public function outgoingFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequestModel::class, 'sender_id');
    }

    // Все запросы в друзья (и отправитель, и получатель)
    public function allFriendshipRequests(): Builder // Возвращает построитель запросов
    {
        return FriendRequestModel::query()
            ->where('sender_id', $this->id)
            ->orWhere('recipient_id', $this->id);
    }

    // Имеет дружбу с конкретным пользователем
    public function isFriendsWith(User $anotherUser): bool
    {
        return $this->friends()->where('users.id', $anotherUser->id)->exists();
    }

    public function hasPendingRequestWith(User $anotherUser): bool
    {
        // Ищем один существующий запрос со статусом 'pending' В ЛЮБОМ НАПРАВЛЕНИИ.
        return FriendRequestModel::where('status', FriendRequestStatus::Pending)
            ->where(function ($query) use ($anotherUser) {
                $query->where('sender_id', $this->id)
                    ->where('recipient_id', $anotherUser->id);
            })
            ->orWhere(function ($query) use ($anotherUser) {
                $query->where('sender_id', $anotherUser->id)
                    ->where('recipient_id', $this->id);
            })
            ->exists();
    }
}
