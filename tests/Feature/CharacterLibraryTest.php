<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\CharacterBuildService;
use App\Services\GameClassService;
use App\Services\RaceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CharacterLibraryTest extends TestCase
{
    use RefreshDatabase;

    protected function beforeRefreshingDatabase(): void
    {
        // These tests never migrate or clear the development MySQL database.
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.url' => null]);
        DB::purge('sqlite');
    }

    public static function libraries(): array
    {
        return [
            'races' => ['race', RaceService::class, 'races'],
            'classes' => ['class', GameClassService::class, 'gameClasses'],
        ];
    }

    private function input(): array
    {
        return [
            'name' => 'Лунный странник',
            'description' => 'Происхождение героя.',
            'rules_text' => 'Раз в день видит скрытую тропу.',
            'mechanics' => ['uses' => 1],
            'metadata' => ['origin' => 'custom'],
        ];
    }

    #[DataProvider('libraries')]
    public function test_complete_library_lifecycle(string $prefix, string $serviceClass, string $relation): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route($prefix.'.store'), [
            ...$this->input(), 'mechanics_json' => '{"uses":1}', 'metadata_json' => '{"origin":"custom"}',
            'user_id' => 999, 'status' => 'final', 'version' => 55,
        ])->assertSessionHasNoErrors();

        $entry = $user->$relation()->sole();
        $version = $entry->versions()->sole();
        $this->assertSame('draft', $version->status);
        $this->assertSame(1, $version->version);
        $this->assertSame(['uses' => 1], $version->mechanics);

        $this->get(route($prefix.'.index'))->assertOk()->assertSee($entry->name);
        $this->get(route($prefix.'.create'))->assertOk();
        $this->get(route($prefix.'.versions.edit', [$entry, $version]))->assertOk();
        $this->patch(route($prefix.'.versions.update', [$entry, $version]), [
            'description' => 'Новое описание.',
        ])->assertRedirect(route($prefix.'.show', $entry))->assertSessionHasNoErrors();
        $this->assertSame(['uses' => 1], $version->refresh()->mechanics);
        $this->assertSame('Новое описание.', $version->description);

        $this->post(route($prefix.'.versions.finalize', [$entry, $version]))
            ->assertSessionHasNoErrors();
        $this->assertSame('final', $entry->refresh()->status);
        $this->assertSame('final', $version->refresh()->status);

        $this->patch(route($prefix.'.versions.update', [$entry, $version]), [
            'description' => 'Нельзя изменить.',
        ])->assertSessionHasErrors();
        $this->assertSame('Новое описание.', $version->refresh()->description);
        $this->get(route($prefix.'.versions.edit', [$entry, $version]))->assertRedirect();

        $this->post(route($prefix.'.versions.copy', [$entry, $version]))->assertRedirect();
        $draft = $entry->versions()->where('status', 'draft')->sole();
        $this->assertSame(2, $draft->version);
        $this->assertSame($version->mechanics, $draft->mechanics);
        $this->post(route($prefix.'.versions.copy', [$entry, $version]))->assertSessionHasErrors();
        $this->assertSame(2, $entry->versions()->count());

        $this->post(route($prefix.'.archive', $entry))->assertRedirect();
        $this->assertSame('archived', $entry->refresh()->status);
        $this->patch(route($prefix.'.versions.update', [$entry, $draft]), [
            'description' => 'Нельзя изменить архив.',
        ])->assertSessionHasErrors();
        $this->post(route($prefix.'.versions.finalize', [$entry, $draft]))->assertSessionHasErrors();
        $this->post(route($prefix.'.restore', $entry))->assertRedirect();
        $this->assertSame('final', $entry->refresh()->status);
        $this->get(route($prefix.'.show', $entry))->assertOk();
    }

    #[DataProvider('libraries')]
    public function test_every_library_endpoint_requires_ownership(string $prefix, string $serviceClass, string $relation): void
    {
        $owner = User::factory()->create();
        $entry = app($serviceClass)->createDraft($owner, $this->input());
        $version = $entry->versions->first();
        $this->actingAs(User::factory()->create());

        $this->get(route($prefix.'.show', $entry))->assertNotFound();
        $this->get(route($prefix.'.versions.edit', [$entry, $version]))->assertNotFound();
        $this->patch(route($prefix.'.versions.update', [$entry, $version]), ['description' => 'X'])->assertNotFound();
        foreach (['finalize', 'copy'] as $action) {
            $this->post(route($prefix.'.versions.'.$action, [$entry, $version]))->assertNotFound();
        }
        foreach (['archive', 'restore'] as $action) {
            $this->post(route($prefix.'.'.$action, $entry))->assertNotFound();
        }
        $this->get(route($prefix.'.index'))->assertOk()->assertDontSee($entry->name);
        $this->assertSame('draft', $version->refresh()->status);
    }

    #[DataProvider('libraries')]
    public function test_version_must_belong_to_requested_entry(string $prefix, string $serviceClass, string $relation): void
    {
        $user = User::factory()->create();
        $service = app($serviceClass);
        $first = $service->createDraft($user, $this->input());
        $other = $service->createDraft($user, $this->input());
        $version = $other->versions->first();
        $this->actingAs($user);
        $this->get(route($prefix.'.versions.edit', [$first, $version]))->assertNotFound();
        $this->patch(route($prefix.'.versions.update', [$first, $version]), ['description' => 'X'])->assertNotFound();
        $this->post(route($prefix.'.versions.finalize', [$first, $version]))->assertNotFound();
        $this->post(route($prefix.'.versions.copy', [$first, $version]))->assertNotFound();
    }

    #[DataProvider('libraries')]
    public function test_json_validation_and_escaped_output(string $prefix, string $serviceClass, string $relation): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        foreach (['broken', '"string"', '42', 'null'] as $json) {
            $this->post(route($prefix.'.store'), [
                'name' => 'Проверка', 'mechanics_json' => $json,
            ])->assertSessionHasErrors('mechanics_json');
        }
        $this->assertSame(0, $user->$relation()->count());
        $entry = app($serviceClass)->createDraft($user, [
            ...$this->input(), 'description' => '<script>alert(1)</script>',
        ]);
        $this->get(route($prefix.'.show', $entry))->assertOk()
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
        $version = $entry->versions->first();
        $this->patch(route($prefix.'.versions.update', [$entry, $version]), [
            'mechanics_json' => '', 'metadata_json' => '',
        ])->assertSessionHasNoErrors();
        $this->assertNull($version->refresh()->mechanics);
        $this->assertNull($version->metadata);
    }

    #[DataProvider('libraries')]
    public function test_incomplete_version_cannot_be_approved_and_draft_restores_as_draft(string $prefix, string $serviceClass, string $relation): void
    {
        $user = User::factory()->create();
        $service = app($serviceClass);
        $entry = $service->createDraft($user, ['name' => 'Новая идея']);
        $this->actingAs($user)->post(route($prefix.'.versions.finalize', [$entry, $entry->versions->first()]))
            ->assertSessionHasErrors(['description', 'rules_text']);
        $this->assertSame('draft', $entry->refresh()->status);
        $service->archive($user, $entry->id);
        $this->assertSame('draft', $service->restore($user, $entry->id)->status);
    }

    #[DataProvider('libraries')]
    public function test_pagination_and_guest_protection(string $prefix, string $serviceClass, string $relation): void
    {
        $this->get(route($prefix.'.index'))->assertRedirect(route('login'));
        $this->post(route($prefix.'.store'), ['name' => 'Guest'])->assertRedirect(route('login'));
        $user = User::factory()->create();
        for ($i = 0; $i < 13; $i++) {
            app($serviceClass)->createDraft($user, ['name' => 'Запись '.$i]);
        }
        $this->actingAs($user)->get(route($prefix.'.index'))->assertOk()->assertSee('page=2', false);
        $this->get(route($prefix.'.index', ['page' => 2]))->assertOk();
    }

    public function test_race_assignment_checks_ownership_status_and_keeps_pinned_version(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $character = $user->characters()->create(['name' => 'Рен']);
        $foreignCharacter = $other->characters()->create(['name' => 'Чужой']);
        $service = app(RaceService::class);
        $race = $service->createDraft($user, $this->input());
        $version = $race->versions->first();
        $this->actingAs($user);
        $this->patch(route('character.race.update', $character), ['race_version_id' => $version->id])
            ->assertSessionHasErrors('race_version_id');
        $service->finalize($user, $race->id, $version->id);
        $this->patch(route('character.race.update', $character), ['race_version_id' => $version->id])
            ->assertSessionHasNoErrors();
        $this->assertSame($version->id, $character->refresh()->race_version_id);
        $next = $service->createVersion($user, $race->id, $version->id);
        $service->finalize($user, $race->id, $next->id);
        $this->assertSame($version->id, $character->refresh()->race_version_id);

        $foreignRace = $service->createDraft($other, $this->input());
        $foreignVersion = $foreignRace->versions->first();
        $service->finalize($other, $foreignRace->id, $foreignVersion->id);
        $this->patch(route('character.race.update', $character), ['race_version_id' => $foreignVersion->id])->assertNotFound();
        $this->patch(route('character.race.update', $foreignCharacter), ['race_version_id' => $version->id])->assertNotFound();
        $service->archive($user, $race->id);
        $this->patch(route('character.race.update', $character), ['race_version_id' => $version->id])
            ->assertSessionHasErrors('race_version_id');
        $this->assertSame($version->id, $character->refresh()->race_version_id);
        $this->get(route('character.workshop', $character))->assertOk()->assertSee($race->name);
        $this->patch(route('character.race.update', $character), ['race_version_id' => null])->assertSessionHasNoErrors();
        $this->assertNull($character->refresh()->race_version_id);
        $character->status = 'active';
        $character->save();
        $this->patch(route('character.race.update', $character), ['race_version_id' => null])->assertSessionHasErrors('character');
    }

    public function test_multiclass_levels_version_changes_and_removal_are_independent(): void
    {
        $user = User::factory()->create();
        $character = $user->characters()->create(['name' => 'Рен']);
        $service = app(GameClassService::class);
        $build = app(CharacterBuildService::class);
        $first = $service->createDraft($user, $this->input());
        $second = $service->createDraft($user, [...$this->input(), 'name' => 'Хранитель']);
        foreach ([$first, $second] as $entry) {
            $service->finalize($user, $entry->id, $entry->versions->first()->id);
        }
        $firstVersion = $first->versions->first();
        $secondVersion = $second->versions->first();
        $this->actingAs($user)->put(route('character.classes.update', $character), [
            'class_version_id' => $firstVersion->id, 'level' => 3,
        ])->assertSessionHasNoErrors();
        $build->setClass($user, $character->id, $secondVersion->id, 2);
        $this->assertSame(2, $character->classes()->count());

        $next = $service->createVersion($user, $first->id, $firstVersion->id);
        $service->finalize($user, $first->id, $next->id);
        $this->assertSame($firstVersion->id, $character->classes()->where('game_class_id', $first->id)->sole()->class_version_id);
        $selection = $build->setClass($user, $character->id, $next->id, 4);
        $this->assertSame(2, $character->classes()->count());
        $this->assertSame(4, $selection->level);
        $this->assertSame(2, $character->classes()->where('game_class_id', $second->id)->sole()->level);
        $this->get(route('character.workshop', $character))->assertOk()->assertSee('Хранитель');

        $this->delete(route('character.classes.destroy', [$character, $selection]))->assertSessionHasNoErrors();
        $this->assertSame(1, $character->classes()->count());
        $this->assertSame('final', $next->refresh()->status);
    }

    public function test_class_assignment_rejects_foreign_draft_archived_invalid_level_and_active_character(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $character = $user->characters()->create([]);
        $otherCharacter = $other->characters()->create([]);
        $service = app(GameClassService::class);
        $entry = $service->createDraft($user, $this->input());
        $version = $entry->versions->first();
        $this->actingAs($user);
        $data = ['class_version_id' => $version->id, 'level' => 1];
        $this->put(route('character.classes.update', $character), $data)->assertSessionHasErrors('class_version_id');
        $service->finalize($user, $entry->id, $version->id);
        foreach ([0, -1, 1.5, 65536, 'invalid'] as $level) {
            $this->put(route('character.classes.update', $character), [...$data, 'level' => $level])->assertSessionHasErrors('level');
        }
        $this->put(route('character.classes.update', $otherCharacter), $data)->assertNotFound();
        $foreign = $service->createDraft($other, $this->input());
        $foreignVersion = $foreign->versions->first();
        $service->finalize($other, $foreign->id, $foreignVersion->id);
        $this->put(route('character.classes.update', $character), [
            'class_version_id' => $foreignVersion->id, 'level' => 1,
        ])->assertNotFound();
        $foreignSelection = app(CharacterBuildService::class)->setClass($other, $otherCharacter->id, $foreignVersion->id, 1);
        $this->delete(route('character.classes.destroy', [$character, $foreignSelection]))->assertNotFound();
        $service->archive($user, $entry->id);
        $this->put(route('character.classes.update', $character), $data)->assertSessionHasErrors('class_version_id');
        $service->restore($user, $entry->id);
        $character->status = 'active';
        $character->save();
        $this->put(route('character.classes.update', $character), $data)->assertSessionHasErrors('character');
        $this->assertSame(0, $character->classes()->count());
    }

    #[DataProvider('libraries')]
    public function test_services_reject_invalid_payload_without_partial_writes(string $prefix, string $serviceClass, string $relation): void
    {
        $user = User::factory()->create();
        foreach ([['name' => '   '], ['name' => 'OK', 'mechanics' => 'invalid']] as $input) {
            try {
                app($serviceClass)->createDraft($user, $input);
                $this->fail('Invalid payload was accepted.');
            } catch (ValidationException) {
                $this->assertSame(0, $user->$relation()->count());
            }
        }
    }
}
