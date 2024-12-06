@extends('dashboard.layout')

@section('title', 'Профиль пользователя')

@section('content')
    <h1 class="page-title">Профиль пользователя</h1>

    <div class="user-profile">
        <div class="user-info">
            <h2 class="user-name">{{ $user->name }}</h2>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Роль:</strong> {{ $user->role }}</p>
        </div>

        <h3 class="section-title">Проекты:</h3>
        @if ($user->projects->isEmpty())
            <p class="no-projects"><em>Не участвует в проектах</em></p>
        @else
            <ul class="projects-list">
                @foreach ($user->projects as $project)
                    <li>{{ $project->name }}</li>
                @endforeach
            </ul>
        @endif

        <h3 class="section-title">Задачи:</h3>
        @if ($user->tasks->isEmpty())
            <p class="no-tasks"><em>Нет задач</em></p>
        @else
            <ul class="tasks-list">
                @foreach ($user->tasks as $task)
                    <li>{{ $task->name }}</li>
                @endforeach
            </ul>
        @endif

        <div class="actions">
            <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn-edit">Редактировать</a>
            <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" onclick="return confirm('Вы уверены, что хотите удалить пользователя?')">Удалить</button>
            </form>
        </div>
    </div>
@endsection

