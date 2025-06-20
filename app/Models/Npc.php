<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Npc extends Model
{
    protected $table = 'npcs';
    protected $guarded = false;

    /**
     * Получить все токены шаблонов, связанные с этим прототипом NPC.
     */
    public function templateTokens(): MorphMany
    {
        return $this->morphMany(TemplateToken::class, 'tokenable');
    }

    /**
     * Получить все предложения по размещению для этого NPC в шаблонах карт.
     */
    public function suggestedPlacements(): MorphMany
    {
        return $this->morphMany(GameTemplateMapSuggestedEntity::class, 'suggestable');
    }
}
