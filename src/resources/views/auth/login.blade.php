<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="auth-page">
<div class="form-container">
    <h1>Вход в систему</h1>

    <form action="{{ route('login') }}" method="POST" novalidate>
        @csrf
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}">
            @error('email')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password">
            @error('password')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Войти</button>
    </form>
</div>
</body>
</html>

