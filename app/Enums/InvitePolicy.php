<?php

namespace App\Enums;

enum InvitePolicy: string
{
    case ownerOnly = 'owner_only';
    case allMembers = 'all_members';
}
