<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h1>Sign Up</h1>
    <form action="/signup" method="POST">
        @csrf
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        @error('name')
        <div class="text-red-600">{{ $message }}</div>
        @enderror
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
        <div class="text-red-600">{{ $message }}</div>
        @enderror   
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        @error('password')
        <div class="text-red-600">{{ $message }}</div>
        @enderror
        <br>
        <label for="password">Confirm Password:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
        @error('password_confirmation')
        <div class="text-red-600">{{ $message }}</div>
        @enderror
        <br>
        <button type="submit">Sign Up</button>
    </form>
    <a href="/login">Already have an account? Login</a>
</body>
</html>