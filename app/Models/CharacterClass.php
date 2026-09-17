<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CharacterClass extends Model
{
    protected $guarded = ['*'];

    protected function casts(): array
    {
        return ['level' => 'integer'];
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function gameClass(): BelongsTo
    {
        return $this->belongsTo(GameClass::class);
    }

    public function classVersion(): BelongsTo
    {
        return $this->belongsTo(ClassVersion::class);
    }
}
