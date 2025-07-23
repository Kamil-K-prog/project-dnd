<?php

namespace App\Http\Controllers\API\Friends\Requests;

use App\Enums\FriendRequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Friends\Requests\AcceptFriendRequest;
use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequestModel;
use App\Models\FriendUser;
use Illuminate\Support\Facades\DB;

class AcceptController extends Controller
{
    /**
     * Контроллер для принятия заявки в друзья
     */
    public function __invoke(AcceptFriendRequest $request, FriendRequestModel $friendRequest)
    {
        DB::transaction(function () use ($friendRequest) {
            // 1. Обновляем статус и сохраняем модель запроса
            $friendRequest->status = FriendRequestStatus::Accepted;
            $friendRequest->save();

            // 2. Создаем две записи в сводной таблице

            // Прямая запись "Отправитель -> Получатель (текущий пользователь)"
            FriendUser::create([
                'user_id' => $friendRequest->sender_id,
                'friend_id' => $friendRequest->recipient_id, // он же auth()->id()
            ]);

            // Обратная запись
            FriendUser::create([
                'user_id' => $friendRequest->recipient_id,
                'friend_id' => $friendRequest->sender_id,
            ]);
        });

        // Модель уже обновлена, подгружаем связи для ответа
        $friendRequest->load(['sender', 'recipient']);

        return new FriendRequestResource($friendRequest);
    }
}
