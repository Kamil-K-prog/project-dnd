<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendCodeResource;
use Illuminate\Http\Request;

class RegenerateFriendCodeController extends Controller
{
    /**
     * Пересоздаёт код дружбы.
     * Маршрут `/user/regenerate-friend-code`
     * Имя маршрута 'api.user.regenerate-friend-code`
     */
    public function __invoke(Request $request)
    {
        $newCode = auth()->user()->regenerateFriendCode();
        return new FriendCodeResource($newCode);
    }
}
