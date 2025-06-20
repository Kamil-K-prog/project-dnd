<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TemplateToken extends Model
{
    protected $table = 'template_tokens';
    protected $guarded = false;

    /**
     * Получить родительскую модель tokenable (GameCharacter, GameNpc, или GameItem).
     */
    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }
}
