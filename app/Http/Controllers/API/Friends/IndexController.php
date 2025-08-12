<?php

namespace App\Http\Controllers\API\Friends;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Возвращает список друзей текущего пользователя
     */
    public function __invoke(Request $request)
    {
        return UserResource::collection(auth()->user()->friends()->paginate(50));
    }
}
