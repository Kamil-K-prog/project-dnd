<?php

namespace App\Http\Controllers\API\Friends\Requests;

use App\Enums\FriendRequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Friends\Requests\DestroyFriendRequest;
use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequestModel;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DestroyFriendRequest $request, FriendRequestModel $friendRequest)
    {
        // 1. "Закрываем" запрос
        $friendRequest->status = FriendRequestStatus::Cancelled;
        $friendRequest->save();

        // 2. Подгружаем связи
        $friendRequest->load(['sender', 'recipient']);

        // 3. Отдаём ресурс
        return new FriendRequestResource($friendRequest);
    }
}
