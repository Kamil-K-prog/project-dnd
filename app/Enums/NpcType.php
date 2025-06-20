<?php

namespace App\Enums;

enum NpcType:string
{
    case Generic = 'generic';
    case Monster = 'monster';
    case Human = 'human';
    case ExoticRace = 'exotic_race';
}
