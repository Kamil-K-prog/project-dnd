<?php

namespace App\Enums;

enum GameSessionRole:string
{
    case Dm = 'dm';
    case Player = 'player';
}
