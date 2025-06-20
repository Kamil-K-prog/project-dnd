<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Token extends Model
{
    protected $table = 'tokens';
    protected $guarded = false;

    /**
     * Получить родительскую модель tokenable (GameCharacter, GameNpc, или GameItem).
     */
    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }
}
