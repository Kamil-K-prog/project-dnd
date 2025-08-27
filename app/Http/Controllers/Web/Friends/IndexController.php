<?php

namespace App\Http\Controllers\Web\Friends;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendRequestResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        // Предзагрузка всех нужных данных
        $friends = $user->friends()->get();
        $friendRequests = $user->allFriendshipRequests()
            ->with(['sender', 'recipient'])
            ->latest()
            ->paginate(50, ['*'], 'requestsPage');

        return Inertia::render('Friends/Index', [
            'friends' => UserResource::collection($friends),
            'friendRequests' => FriendRequestResource::collection($friendRequests),
            'currentUser' => new UserResource($user),
        ]);
    }
}
