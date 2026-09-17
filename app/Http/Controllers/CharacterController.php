<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;
use Illuminate\Support\Facades\Auth;

class CharacterController extends Controller
{
    function index(Request $request)
    {
        $characters = $request->user()
        ->characters()
        ->latest()
        ->paginate(12);

         return view('characters.show', compact('characters'));
    }
    function create()
    {
        return view('characters.create');
    }
    function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $character = $request->user()->characters()->create($data);

         return redirect()
            ->route('character.workshop', $character)
            ->with('success', 'Черновик персонажа создан.');
    }

    public function workshop(Request $request, string $character)
    {
        $character = $request->user()
            ->characters()
            ->with(['raceVersion.race', 'classes.classVersion.gameClass'])
            ->findOrFail($character);

        $messages = $character->messages()
            ->orderBy('id')
            ->get();

        $races = $request->user()->races()
            ->where('status', '!=', 'archived')
            ->whereHas('versions', fn ($query) => $query->where('status', 'final'))
            ->with(['versions' => fn ($query) => $query->where('status', 'final')->orderByDesc('version')])
            ->orderBy('name')->get();

        $gameClasses = $request->user()->gameClasses()
            ->where('status', '!=', 'archived')
            ->whereHas('versions', fn ($query) => $query->where('status', 'final'))
            ->with(['versions' => fn ($query) => $query->where('status', 'final')->orderByDesc('version')])
            ->orderBy('name')->get();

        return view('characters.workshop', compact('character', 'messages', 'races', 'gameClasses'));
    }

    public function storeMessage(Request $request, string $character)
    {
        $character = $request->user()
            ->characters()
            ->findOrFail($character);

        $data = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $character->messages()->create([
            'role' => 'user',
            'content' => $data['content'],
        ]);

        return redirect()->route('character.workshop', $character);
    }

}
