<?php

namespace App\Http\Controllers;

use App\Services\CharacterBuildService;
use Illuminate\Http\Request;

class CharacterBuildController extends Controller
{
    public function race(Request $request, CharacterBuildService $service, string $character)
    {
        $data = $request->validate([
            'race_version_id' => ['present', 'nullable', 'integer', 'min:1'],
        ]);
        $service->setRace($request->user(), (int) $character,
            $data['race_version_id'] === null ? null : (int) $data['race_version_id']);

        return redirect()->route('character.workshop', $character)->with('success', 'Раса персонажа сохранена.');
    }

    public function gameClass(Request $request, CharacterBuildService $service, string $character)
    {
        $data = $request->validate([
            'class_version_id' => ['required', 'integer', 'min:1'],
            'level' => ['required', 'integer', 'min:1', 'max:65535'],
        ]);
        $service->setClass($request->user(), (int) $character, (int) $data['class_version_id'], (int) $data['level']);

        return redirect()->route('character.workshop', $character)->with('success', 'Класс и его уровень сохранены.');
    }

    public function removeClass(Request $request, CharacterBuildService $service, string $character, string $selection)
    {
        $service->removeClass($request->user(), (int) $character, (int) $selection);

        return redirect()->route('character.workshop', $character)->with('success', 'Класс убран из черновика персонажа.');
    }
}
