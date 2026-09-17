<?php

namespace App\Http\Controllers;

use App\Http\Requests\LibraryEntryRequest;
use App\Services\GameClassService;
use Illuminate\Http\Request;

class GameClassController extends Controller
{
    private function viewData(): array
    {
        return [
            'routePrefix' => 'class',
            'libraryTitle' => 'Классы',
            'newTitle' => 'Новый класс',
        ];
    }

    public function index(Request $request)
    {
        $entries = $request->user()->gameClasses()->latest()->paginate(12);

        return view('classes.index', [...$this->viewData(), 'entries' => $entries]);
    }

    public function create()
    {
        return view('classes.create', $this->viewData());
    }

    public function store(LibraryEntryRequest $request, GameClassService $service)
    {
        $entry = $service->createDraft($request->user(), $request->entryData());

        return redirect()->route('class.show', $entry)
            ->with('success', 'Черновик создан.');
    }

    public function show(Request $request, string $entry)
    {
        $entry = $request->user()->gameClasses()
            ->with(['versions' => fn ($query) => $query->orderByDesc('version')])
            ->findOrFail($entry);

        return view('classes.show', [...$this->viewData(), 'entry' => $entry]);
    }

    public function edit(Request $request, string $entry, string $version)
    {
        $entry = $request->user()->gameClasses()->findOrFail($entry);
        $version = $entry->versions()->findOrFail($version);
        if ($entry->status === 'archived' || $version->status !== 'draft') {
            return redirect()->route('class.show', $entry)
                ->withErrors(['version' => 'Редактировать можно только действующий черновик.']);
        }

        return view('classes.edit', [...$this->viewData(), 'entry' => $entry, 'version' => $version]);
    }

    public function update(LibraryEntryRequest $request, GameClassService $service, string $entry, string $version)
    {
        $service->updateDraft($request->user(), (int) $entry, (int) $version, $request->entryData());

        return redirect()->route('class.show', $entry)->with('success', 'Черновик сохранён.');
    }

    public function finalize(Request $request, GameClassService $service, string $entry, string $version)
    {
        $service->finalize($request->user(), (int) $entry, (int) $version);

        return redirect()->route('class.show', $entry)
            ->with('success', 'Версия утверждена. Теперь её можно выбрать в мастерской персонажа.');
    }

    public function createVersion(Request $request, GameClassService $service, string $entry, string $version)
    {
        $draft = $service->createVersion($request->user(), (int) $entry, (int) $version);

        return redirect()->route('class.versions.edit', [$entry, $draft->id])
            ->with('success', 'Новый черновик создан на основе выбранной версии.');
    }

    public function archive(Request $request, GameClassService $service, string $entry)
    {
        $service->archive($request->user(), (int) $entry);

        return redirect()->route('class.show', $entry)->with('success', 'Запись убрана в архив.');
    }

    public function restore(Request $request, GameClassService $service, string $entry)
    {
        $service->restore($request->user(), (int) $entry);

        return redirect()->route('class.show', $entry)->with('success', 'Запись восстановлена.');
    }
}
