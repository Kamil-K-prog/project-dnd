<?php

namespace App\Http\Controllers\API\Friends\Requests;

use App\Enums\FriendRequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Friends\Requests\DeclineFriendRequest;
use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequestModel;
use Illuminate\Http\Request;

class DeclineController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DeclineFriendRequest $request, FriendRequestModel $friendRequest)
    {
        // 1. Обновляем статус запроса
        $friendRequest->status = FriendRequestStatus::Declined;
        $friendRequest->save();

        // 2. Подгружаем связи
        $friendRequest->load(['sender', 'recipient']);

        // 3. Возвращаем обновленную модель
        return new FriendRequestResource($friendRequest);
    }
}
