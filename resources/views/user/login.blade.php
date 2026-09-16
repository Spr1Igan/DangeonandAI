<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form action="/login" method="POST">
        @csrf
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label>
        <input type="checkbox" name="remember" value="1">Запомнить меня</label>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
        <button type="submit">Login</button>
    </form>
    <a href="/signup">Don't have an account? Sign Up</a>
</body>
</html>