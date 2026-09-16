<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name'])]
class Race extends Model
{
    
    //

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function versions(): HasMany
    {
        return $this->hasMany(RaceVersion::class);
    }
}
