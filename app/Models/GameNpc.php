<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class GameNpc extends Model
{
    protected $table = 'game_npcs';
    protected $guarded = false;

    /**
     * Получить все токены, связанные с этим игровым персонажем.
     */
    public function tokens(): MorphMany
    {
        return $this->morphMany(Token::class, 'tokenable');
    }
}
