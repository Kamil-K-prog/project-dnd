<?php

namespace App\Http\Controllers\API\Friends\Requests;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendRequestResource;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Контроллер для получения списка запросов в друзья (исходящие и входящие)
     */
    public function __invoke(Request $request)
    {
        $friendshipRequests = auth()->user()->allFriendshipRequests()
            ->with(['sender', 'recipient'])
            ->latest()
            ->get();
        return FriendRequestResource::collection($friendshipRequests);
    }
}
