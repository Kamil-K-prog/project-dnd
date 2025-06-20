<?php

namespace App\Enums;

enum CharacterStatus: string
{
    case Alive = 'alive';
    case Dead = 'dead';
    case Observer = 'observer';
    case PlayingByAi = 'playing_by_AI';
}
