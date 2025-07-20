<?php

namespace App\Http\Controllers\API\Friends;

use App\Http\Controllers\Controller;

class FriendController extends Controller
{
    public function index()
    {
        return auth()->user()->friends()->toResourceCollection();
    }
}
