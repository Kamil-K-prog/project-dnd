<?php

namespace App\Enums;

enum ItemType: string
{
    case Weapon = 'weapon';
    case Armor = 'armor';
    case Potion = 'potion';
    case Trap = 'trap';
    case Key = 'key';
    case Generic = 'generic';
}
