<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация компании</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body class="auth-page">
<div class="form-container">
    <h1>Регистрация компании</h1>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <form action="{{ route('company.store') }}" method="POST" novalidate>
        @csrf
        <div class="form-group">
            <label for="name">Название компании:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}">
            @error('name')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

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

        <div class="form-group">
            <label for="password_confirmation">Подтверждение пароля:</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit">Зарегистрировать</button>
        <p>
            Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
        </p>

    </form>
</div>
</body>

</html>
