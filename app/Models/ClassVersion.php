<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

#[Fillable(['version', 'description', 'rules_text', 'mechanics', 'metadata'])]
class ClassVersion extends Model
{
    protected $attributes = ['status' => 'draft'];

    protected static function booted(): void
    {
        static::updating(function (ClassVersion $version) {
            if ($version->getOriginal('status') === 'final') {
                throw ValidationException::withMessages([
                    'class_version' => 'Готовую версию нельзя изменить. Создай новую версию.',
                ]);
            }
        });

        static::deleting(function (ClassVersion $version) {
            if ($version->getOriginal('status') === 'final') {
                throw ValidationException::withMessages([
                    'class_version' => 'Готовую версию нельзя удалить. Класс можно убрать в архив.',
                ]);
            }
        });
    }

    protected function casts(): array
    {
        return ['version' => 'integer', 'mechanics' => 'array', 'metadata' => 'array'];
    }

    public function gameClass(): BelongsTo
    {
        return $this->belongsTo(GameClass::class);
    }

    public function characterClasses(): HasMany
    {
        return $this->hasMany(CharacterClass::class);
    }
}
