<?php

namespace App\Http\Controllers;

use App\Http\Requests\LibraryEntryRequest;
use App\Services\RaceService;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    private function viewData(): array
    {
        return [
            'routePrefix' => 'race',
            'libraryTitle' => 'Расы',
            'newTitle' => 'Новая раса',
        ];
    }

    public function index(Request $request)
    {
        $entries = $request->user()->races()->latest()->paginate(12);

        return view('races.index', [...$this->viewData(), 'entries' => $entries]);
    }

    public function create()
    {
        return view('races.create', $this->viewData());
    }

    public function store(LibraryEntryRequest $request, RaceService $service)
    {
        $entry = $service->createDraft($request->user(), $request->entryData());

        return redirect()->route('race.show', $entry)
            ->with('success', 'Черновик создан.');
    }

    public function show(Request $request, string $entry)
    {
        $entry = $request->user()->races()
            ->with(['versions' => fn ($query) => $query->orderByDesc('version')])
            ->findOrFail($entry);

        return view('races.show', [...$this->viewData(), 'entry' => $entry]);
    }

    public function edit(Request $request, string $entry, string $version)
    {
        $entry = $request->user()->races()->findOrFail($entry);
        $version = $entry->versions()->findOrFail($version);
        if ($entry->status === 'archived' || $version->status !== 'draft') {
            return redirect()->route('race.show', $entry)
                ->withErrors(['version' => 'Редактировать можно только действующий черновик.']);
        }

        return view('races.edit', [...$this->viewData(), 'entry' => $entry, 'version' => $version]);
    }

    public function update(LibraryEntryRequest $request, RaceService $service, string $entry, string $version)
    {
        $service->updateDraft($request->user(), (int) $entry, (int) $version, $request->entryData());

        return redirect()->route('race.show', $entry)->with('success', 'Черновик сохранён.');
    }

    public function finalize(Request $request, RaceService $service, string $entry, string $version)
    {
        $service->finalize($request->user(), (int) $entry, (int) $version);

        return redirect()->route('race.show', $entry)
            ->with('success', 'Версия утверждена. Теперь её можно выбрать в мастерской персонажа.');
    }

    public function createVersion(Request $request, RaceService $service, string $entry, string $version)
    {
        $draft = $service->createVersion($request->user(), (int) $entry, (int) $version);

        return redirect()->route('race.versions.edit', [$entry, $draft->id])
            ->with('success', 'Новый черновик создан на основе выбранной версии.');
    }

    public function archive(Request $request, RaceService $service, string $entry)
    {
        $service->archive($request->user(), (int) $entry);

        return redirect()->route('race.show', $entry)->with('success', 'Запись убрана в архив.');
    }

    public function restore(Request $request, RaceService $service, string $entry)
    {
        $service->restore($request->user(), (int) $entry);

        return redirect()->route('race.show', $entry)->with('success', 'Запись восстановлена.');
    }
}
