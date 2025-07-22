<?php

namespace App\Http\Controllers\API\Friends\Requests;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Friends\Requests\StoreFriendRequest;
use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequestModel;
use App\Models\User;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreFriendRequest $request)
    {
        // 1. Валидируем и получаем данные
        $data = $request->validated();

        // 2. Валидация прошла, значит, можно создавать запись
        $response = FriendRequestModel::create([
            'sender_id' => auth()->user()->id,
            'recipient_id' => $request->recipient->id, // Берем ID из свойства реквеста
        ]);

        // 3. Подгружаем связи
        $response->load(['sender', 'recipient']);

        // 4. Возвращаем ресурс
        return new FriendRequestResource($response);
    }
}
