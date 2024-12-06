@extends('dashboard.layout')

@section('title', 'Добавить пользователя')

@section('content')
    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif
    <div class="project-page">
        <form action="{{ route('dashboard.users.store') }}" method="POST">
            @csrf
            <h1>Добавить пользователя</h1>

            <div>
                <label for="name">Имя:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}">
                @error('name')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
                @error('email')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password">
                @error('password')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation">Подтвердите пароль:</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
                @error('password_confirmation')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role">Роль:</label>
                <select id="role" name="role">
                    <option value="">-- Выберите роль --</option>
                    <option value="admin">Администратор</option>
                    <option value="user">Работник</option>
                </select>
                @error('role')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">Добавить</button>
        </form>
    </div>
@endsection
