<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Relations\HasMany;


#[Fillable(['version', 'description', 'rules_text', 'metadata', 'mechanics'])]
class RaceVersion extends Model
{
    //
    protected $attributes = [
        'status' => 'draft',
    ];
    protected static function booted(): void
    {
        static::updating(function (RaceVersion $version) {
            if ($version->getOriginal('status') === 'final') {
                throw ValidationException::withMessages([
                    'race_version' =>
                        'Готовую версию нельзя изменить. Создай новую версию.',
                ]);
            }
        });

        static::deleting(function (RaceVersion $version) {
            if ($version->getOriginal('status') === 'final') {
                throw ValidationException::withMessages([
                    'race_version' =>
                        'Готовую версию нельзя удалить. Расу можно убрать в архив.',
                ]);
            }
        });
    }


    public function race(): BelongsTo
    {
         return $this->belongsTo(Race::class);
    }
    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'metadata' => 'array',
            'mechanics' => 'array',
        ];
    }
    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }
}
