<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description','ability_scores','metadata','items','currency','hp_max','hp_current','resources'])]
class Character extends Model
{
    protected $attributes = [
        'status' => 'draft',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function messages(): HasMany
    {
        return $this->hasMany(CharacterMessage::class);
    }
    protected function casts(): array
    {
        return [
            'ability_scores' => 'array',
            'metadata' => 'array',
            'items' => 'array',
            'currency' => 'array',
            'resources' => 'array',
            'hp_current' => 'integer',
            'hp_max' => 'integer',
        ];
    }
    public function raceVersion(): BelongsTo
    {
        return $this->belongsTo(RaceVersion::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(CharacterClass::class);
    }
}
