@extends('layouts.library')
@section('title', $entry->name)
@section('content')
    <p class="eyebrow">Библиотека · {{ $libraryTitle }}</p>
    <h1>{{ $entry->name }}</h1>
    <div class="heading">
        <span class="badge">{{ match($entry->status) { 'final' => 'Есть утверждённая версия', 'archived' => 'В архиве', default => 'Черновик' } }}</span>
        <div class="actions">
            <a href="{{ route($routePrefix.'.index') }}">← К библиотеке</a>
            <form method="POST" action="{{ route($routePrefix.($entry->status === 'archived' ? '.restore' : '.archive'), $entry) }}">
                @csrf
                <button class="secondary" type="submit">{{ $entry->status === 'archived' ? 'Восстановить' : 'В архив' }}</button>
            </form>
        </div>
    </div>
    <p class="intro">{{ $entry->status === 'archived' ? 'Запись скрыта из выбора в мастерской. У персонажей сохраняются ранее выбранные версии.' : 'Утверждённую версию можно выбрать в мастерской персонажа. Для изменения готовых правил создай новую версию.' }}</p>
    @php($hasDraft = $entry->versions->contains('status', 'draft'))
    @forelse($entry->versions as $version)
        <article class="card">
            <div class="heading">
                <h2>Версия {{ $version->version }}</h2>
                <span class="badge">{{ $version->status === 'final' ? 'Утверждена' : 'Черновик' }}</span>
            </div>
            <h3>Описание</h3>
            <p class="prose">{{ $version->description ?: 'Описание пока не добавлено.' }}</p>
            <h3>Способности и правила</h3>
            <p class="prose">{{ $version->rules_text ?: 'Правила пока не добавлены.' }}</p>
            @foreach(['mechanics' => 'Механики', 'metadata' => 'Дополнительные сведения'] as $field => $label)
                @if($version->$field !== null)
                    <details><summary>{{ $label }}</summary><pre>{{ json_encode($version->$field, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre></details>
                @endif
            @endforeach
            @if($entry->status !== 'archived')
                <div class="actions">
                    @if($version->status === 'draft')
                        <a class="button" href="{{ route($routePrefix.'.versions.edit', [$entry, $version]) }}">Редактировать</a>
                        <form method="POST" action="{{ route($routePrefix.'.versions.finalize', [$entry, $version]) }}">
                            @csrf
                            <button class="secondary" type="submit">Утвердить версию</button>
                        </form>
                    @elseif(!$hasDraft)
                        <form method="POST" action="{{ route($routePrefix.'.versions.copy', [$entry, $version]) }}">
                            @csrf
                            <button class="secondary" type="submit">Новый черновик на основе этой версии</button>
                        </form>
                    @endif
                </div>
                @if($version->status === 'draft')<p class="hint">После утверждения эта версия станет доступна персонажам и будет закрыта для изменений.</p>@endif
            @endif
        </article>
    @empty
        <p class="muted">Версий пока нет.</p>
    @endforelse
@endsection

