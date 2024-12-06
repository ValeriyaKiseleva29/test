@extends('dashboard.layout')

@section('title', 'Список пользователей')

@section('content')
    <h1 class="page-title">Список пользователей компании</h1>

    <div class="projects-grid"> <!-- Используем ту же сетку -->
        @forelse ($users as $user)
            <div class="project-card"> <!-- Используем тот же стиль для карточек -->
                <h2 class="project-title">{{ $user->name }}</h2>
                <p class="project-description"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="project-description"><strong>Роль:</strong> {{ $user->role }}</p>
                <h3 class="task-heading">Проекты:</h3>
                @if ($user->projects->isEmpty())
                    <p class="no-tasks"><em>Не участвует в проектах</em></p>
                @else
                    <ul class="task-list">
                        @foreach ($user->projects as $project)
                            <li class="task-item">{{ $project->name }}</li>
                        @endforeach
                    </ul>
                @endif

                <h3 class="task-heading">Задачи:</h3>
                @if ($user->tasks->isEmpty())
                    <p class="no-tasks"><em>Нет задач</em></p>
                @else
                    <ul class="task-list">
                        @foreach ($user->tasks as $task)
                            <li class="task-item">{{ $task->name }}</li>
                        @endforeach
                    </ul>
                @endif
                <div class="actions">
                    <form action="{{ route('dashboard.users.show', $user->id) }}" method="GET" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-view">Просмотреть</button>
                    </form>

                    <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Вы уверены, что хотите удалить пользователя?')">Удалить</button>
                    </form>

                    <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn-edit">Редактировать</a>
                </div>
            </div>

        @empty
            <p class="no-projects">В этой компании пока нет пользователей.</p>
        @endforelse


    </div>
@endsection
