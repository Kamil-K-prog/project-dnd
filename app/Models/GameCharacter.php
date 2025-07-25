<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class GameCharacter extends Model
{
    protected $table = 'game_characters';
    protected $guarded = false;

    /**
     * Получить все токены, связанные с этим игровым персонажем.
     */
    public function tokens(): MorphMany
    {
        return $this->morphMany(Token::class, 'tokenable');
    }
}
