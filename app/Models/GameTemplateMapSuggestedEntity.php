<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GameTemplateMapSuggestedEntity extends Model
{
    protected $table = 'game_template_map_suggested_entities';
    protected $guarded = false;

    /**
     * Получить родительскую модель suggestable (Npc или Item).
     */
    public function suggestable(): MorphTo
    {
        return $this->morphTo();
    }

    // Обычные связи, если нужны
    public function gameTemplate(): BelongsTo
    {
        return $this->belongsTo(GameTemplate::class);
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }
}
