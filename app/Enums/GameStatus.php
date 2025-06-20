<?php

namespace App\Enums;

enum GameStatus:string
{
    case Preparing = 'preparing';
    case Playing = 'playing';
    case Paused = 'paused';
    case Finished = 'finished';
    case Cancelled = 'cancelled';
}
