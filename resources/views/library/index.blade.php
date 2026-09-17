@extends('layouts.library')
@section('title', $libraryTitle)
@section('content')
    <p class="eyebrow">Твоя библиотека</p>
    <div class="heading">
        <h1>{{ $libraryTitle }}</h1>
        <a class="button" href="{{ route($routePrefix.'.create') }}">{{ $newTitle }} <span aria-hidden="true">&nbsp;＋</span></a>
    </div>
    <p class="intro">Придумывай свои правила и сохраняй их для будущих героев. Каждый персонаж использует выбранную тобой версию.</p>
    <div class="grid">
        @forelse($entries as $entry)
            <article class="card">
                <span class="badge">{{ match($entry->status) { 'final' => 'Есть утверждённая версия', 'archived' => 'В архиве', default => 'Черновик' } }}</span>
                <h2 style="margin-top:14px"><a href="{{ route($routePrefix.'.show', $entry) }}">{{ $entry->name }}</a></h2>
                <p class="hint">Создано {{ $entry->created_at->format('d.m.Y') }}</p>
            </article>
        @empty
            <div class="card empty"><h2>Первые идеи — впереди</h2><p class="muted">Создай первую запись в своей библиотеке.</p></div>
        @endforelse
    </div>
    @if($entries->hasPages())
        <nav class="pagination" aria-label="Страницы библиотеки">
            <span>@if($entries->previousPageUrl())<a href="{{ $entries->previousPageUrl() }}">← Назад</a>@endif</span>
            <span class="muted">{{ $entries->currentPage() }} / {{ $entries->lastPage() }}</span>
            <span>@if($entries->nextPageUrl())<a href="{{ $entries->nextPageUrl() }}">Дальше →</a>@endif</span>
        </nav>
    @endif
@endsection

