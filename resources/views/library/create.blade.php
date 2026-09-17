@extends('layouts.library')
@section('title', $newTitle)
@section('content')
    <p class="eyebrow">Библиотека · {{ $libraryTitle }}</p>
    <h1>{{ $newTitle }}</h1>
    <p class="intro">Начни с идеи. Черновик можно дополнить и утвердить, когда правила будут готовы.</p>
    <form class="card" method="POST" action="{{ route($routePrefix.'.store') }}">
        @csrf
        @include('library.form')
    </form>
@endsection

