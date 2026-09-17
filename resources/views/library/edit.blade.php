@extends('layouts.library')
@section('title', 'Редактирование · '.$entry->name)
@section('content')
    <p class="eyebrow">Черновик · версия {{ $version->version }}</p>
    <h1>{{ $entry->name }}</h1>
    <p class="intro">Уточни описание и правила. Изменения этой версии не затронут героев с другой утверждённой версией.</p>
    <form class="card" method="POST" action="{{ route($routePrefix.'.versions.update', [$entry, $version]) }}">
        @csrf
        @method('PATCH')
        @include('library.form')
    </form>
@endsection

