<?php

namespace App\Services;

use App\Models\Race;
use App\Models\RaceVersion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RaceService
{
    private function versionData(array $input): array
    {
        return Validator::make($input, [
            'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'rules_text' => ['sometimes', 'nullable', 'string', 'max:10000'],
            'mechanics' => ['sometimes', 'nullable', 'array'],
            'metadata' => ['sometimes', 'nullable', 'array'],
        ])->validate();
    }

    public function createDraft(User $user, array $input): Race
    {
        if (isset($input['name']) && is_string($input['name'])) {
            $input['name'] = trim($input['name']);
        }
        $name = Validator::make($input, [
            'name' => ['required', 'string', 'max:120'],
        ])->validate()['name'];
        $data = $this->versionData($input);

        return DB::transaction(function () use ($user, $name, $data) {
            $entry = $user->races()->create(['name' => $name]);
            $entry->versions()->create(['version' => 1, ...$data]);

            return $entry->refresh()->load('versions');
        });
    }

    public function updateDraft(User $user, int $entryId, int $versionId, array $input): RaceVersion
    {
        $data = $this->versionData($input);

        return DB::transaction(function () use ($user, $entryId, $versionId, $data) {
            $entry = $user->races()->lockForUpdate()->findOrFail($entryId);
            $this->ensureAvailable($entry);
            $version = $entry->versions()->lockForUpdate()->findOrFail($versionId);

            if ($version->status !== 'draft') {
                throw ValidationException::withMessages([
                    'race_version' => 'Редактировать можно только черновик. Создай новую версию.',
                ]);
            }

            $version->fill($data)->save();

            return $version->refresh();
        });
    }

    public function finalize(User $user, int $entryId, int $versionId): RaceVersion
    {
        return DB::transaction(function () use ($user, $entryId, $versionId) {
            $entry = $user->races()->lockForUpdate()->findOrFail($entryId);
            $this->ensureAvailable($entry);
            $version = $entry->versions()->lockForUpdate()->findOrFail($versionId);

            if ($version->status === 'final') {
                return $version;
            }

            Validator::make($version->toArray(), [
                'description' => ['required', 'string', 'max:5000'],
                'rules_text' => ['required', 'string', 'max:10000'],
            ], [
                'description.required' => 'Перед утверждением добавь описание.',
                'rules_text.required' => 'Перед утверждением добавь особенности и правила.',
            ])->validate();

            $version->status = 'final';
            $version->save();
            $entry->status = 'final';
            $entry->save();

            return $version->refresh();
        });
    }

    public function createVersion(User $user, int $entryId, int $sourceVersionId): RaceVersion
    {
        return DB::transaction(function () use ($user, $entryId, $sourceVersionId) {
            // Все операции над версиями сначала блокируют общую запись библиотеки.
            $entry = $user->races()->lockForUpdate()->findOrFail($entryId);
            $this->ensureAvailable($entry);
            $source = $entry->versions()->lockForUpdate()->findOrFail($sourceVersionId);

            if ($source->status !== 'final') {
                throw ValidationException::withMessages([
                    'race_version' => 'Новую версию создаём на основе утверждённой.',
                ]);
            }

            if ($entry->versions()->where('status', 'draft')->exists()) {
                throw ValidationException::withMessages([
                    'race_version' => 'Уже есть черновик. Сначала заверши его.',
                ]);
            }

            return $entry->versions()->create([
                'version' => (int) $entry->versions()->max('version') + 1,
                'description' => $source->description,
                'rules_text' => $source->rules_text,
                'mechanics' => $source->mechanics,
                'metadata' => $source->metadata,
            ]);
        });
    }

    public function archive(User $user, int $entryId): Race
    {
        return DB::transaction(function () use ($user, $entryId) {
            $entry = $user->races()->lockForUpdate()->findOrFail($entryId);
            $entry->status = 'archived';
            $entry->save();

            return $entry;
        });
    }

    public function restore(User $user, int $entryId): Race
    {
        return DB::transaction(function () use ($user, $entryId) {
            $entry = $user->races()->lockForUpdate()->findOrFail($entryId);
            $entry->status = $entry->versions()->where('status', 'final')->exists()
                ? 'final' : 'draft';
            $entry->save();

            return $entry;
        });
    }

    private function ensureAvailable(Race $entry): void
    {
        if ($entry->status === 'archived') {
            throw ValidationException::withMessages([
                'race_version' => 'Сначала восстанови запись из архива.',
            ]);
        }
    }
}
