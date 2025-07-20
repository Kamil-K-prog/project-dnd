<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function friendCode() // Создаёт новый код
    {
        /* --- Генерация уникального кода дружбы --- */
        do {
            $friendCode = strtoupper(Str::random(8));
        } while (User::where('friend_code', $friendCode)->exists());
        auth()->user()->friendCode = $friendCode;
        return $friendCode;
    }
}
