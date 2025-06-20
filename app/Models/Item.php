<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Item extends Model
{
    protected $table = 'items';
    protected $guarded = false;

    /**
     * Получить все токены шаблонов, связанные с этим прототипом Item.
     */
    public function templateTokens(): MorphMany
    {
        return $this->morphMany(TemplateToken::class, 'tokenable');
    }

    /**
     * Получить все предложения по размещению для этого Item в шаблонах карт.
     */
    public function suggestedPlacements(): MorphMany
    {
        return $this->morphMany(GameTemplateMapSuggestedEntity::class, 'suggestable');
    }
}
