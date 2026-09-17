<?php

namespace App\Services;

use App\Models\Character;
use App\Models\CharacterClass;
use App\Models\ClassVersion;
use App\Models\RaceVersion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CharacterBuildService
{
    public function setRace(User $user, int $characterId, ?int $versionId): Character
    {
        return DB::transaction(function () use ($user, $characterId, $versionId) {
            $character = $this->draftCharacter($user, $characterId);

            if ($versionId === null) {
                $character->raceVersion()->dissociate();
            } else {
                $candidate = RaceVersion::query()->findOrFail($versionId);
                $race = $user->races()->lockForUpdate()->findOrFail($candidate->race_id);
                $version = $race->versions()->lockForUpdate()->findOrFail($versionId);
                $this->ensureSelectable($race->status, $version->status, 'race_version_id');
                $character->raceVersion()->associate($version);
            }

            $character->save();

            return $character->refresh();
        });
    }

    public function setClass(User $user, int $characterId, int $versionId, int $level): CharacterClass
    {
        Validator::make(['level' => $level], [
            'level' => ['required', 'integer', 'min:1', 'max:65535'],
        ])->validate();

        return DB::transaction(function () use ($user, $characterId, $versionId, $level) {
            $character = $this->draftCharacter($user, $characterId);
            $candidate = ClassVersion::query()->findOrFail($versionId);
            $gameClass = $user->gameClasses()->lockForUpdate()->findOrFail($candidate->game_class_id);
            $version = $gameClass->versions()->lockForUpdate()->findOrFail($versionId);
            $this->ensureSelectable($gameClass->status, $version->status, 'class_version_id');

            $selection = $character->classes()
                ->where('game_class_id', $gameClass->id)->first() ?? new CharacterClass;
            $selection->character()->associate($character);
            $selection->gameClass()->associate($gameClass);
            $selection->classVersion()->associate($version);
            $selection->level = $level;
            $selection->save();

            return $selection->refresh();
        });
    }

    public function removeClass(User $user, int $characterId, int $selectionId): void
    {
        DB::transaction(function () use ($user, $characterId, $selectionId) {
            $character = $this->draftCharacter($user, $characterId);
            $character->classes()->findOrFail($selectionId)->delete();
        });
    }

    private function draftCharacter(User $user, int $characterId): Character
    {
        $character = $user->characters()->whereNull('deleted_at')
            ->lockForUpdate()->findOrFail($characterId);

        if ($character->status !== 'draft') {
            throw ValidationException::withMessages([
                'character' => 'Расу и классы можно выбирать только для черновика персонажа.',
            ]);
        }

        return $character;
    }

    private function ensureSelectable(string $entryStatus, string $versionStatus, string $field): void
    {
        if ($entryStatus === 'archived' || $versionStatus !== 'final') {
            throw ValidationException::withMessages([
                $field => 'Выбери утверждённую версию из действующей библиотеки.',
            ]);
        }
    }
}
