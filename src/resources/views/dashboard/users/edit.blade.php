@extends('dashboard.layout')

@section('title', 'Редактировать пользователя')

@section('content')
    <div class="project-page">
        <form action="{{ route('dashboard.users.update', $user->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')
            <h1>Редактировать пользователя</h1>

            <div>
                <label for="name">Имя:</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                >
                @error('name')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email">Email:</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                >
                @error('email')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Роль -->
            <div>
                <label for="role">Роль:</label>
                <select id="role" name="role">
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Пользователь</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Администратор</option>
                </select>
                @error('role')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Новый пароль -->
            <div>
                <label for="password">Новый пароль (необязательно):</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                >
                @error('password')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Кнопка отправки -->
            <button type="submit">Сохранить изменения</button>
        </form>
    </div>
@endsection
