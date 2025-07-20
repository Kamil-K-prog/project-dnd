<?php

namespace App\Models;

use App\Enums\FriendRequestStatus;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    protected $table = 'friend_requests';
    protected $guarded = false;
    protected function  casts():array
    {
        return [
            'status' => FriendRequestStatus::class,
        ];
    }
}
