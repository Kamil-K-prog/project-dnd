<?php

namespace App\Http\Controllers\API\Friends;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Friends\DestroyFriendRequest;
use App\Models\User;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    /**
     * Удаляет пользователя из списка друзей текущего пользователя
     */
    public function __invoke(DestroyFriendRequest $request, User $user)
    {
        auth()->user()->removeFriend($user);
        return response()->noContent();
    }
}
