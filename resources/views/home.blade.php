<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Welcome to the Home Page {{ Auth::user()->name }} </h1>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
    <a href="{{ route('character.index') }}">View Characters</a>
    <a href="{{ route('race.index') }}">Библиотека рас</a>
    <a href="{{ route('class.index') }}">Библиотека классов</a>
</body>
</html>
