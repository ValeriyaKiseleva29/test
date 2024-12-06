@extends('dashboard.layout')

@section('title', 'Список задач')

@section('content')
    <h1 class="page-title">Список задач</h1>

    <div class="projects-grid">
        @forelse ($tasks as $task)
            <div class="project-card">
                <h2 class="project-title">{{ $task->name }}</h2>
                <p class="project-description"><strong>Описание:</strong> {{ $task->description ?? 'Без описания' }}</p>
                <p class="project-description"><strong>Проект:</strong> {{ $task->project->name ?? 'Без проекта' }}</p>
                <p class="project-priority"><strong>Приоритет:</strong> {{ $task->priority }}</p>
                <strong>Начало:</strong> {{ $task->start_date }} |
                <strong>Конец:</strong> {{ $task->end_date }}
                <p class="project-description"><strong>Главный:</strong>
                    {{ $task->leader->name ?? 'Не назначен' }}
                </p>
                <h3 class="files-heading">Прикреплённые файлы:</h3>
                <ul class="files-list">
                    @forelse ($task->files as $file)
                        <li>
                            <a href="{{ url('storage/' . $file->path) }}" download="{{ $file->name }}">
                                {{ $file->name }}
                            </a> ({{ round($file->size / 1024, 2) }} KB)
                        </li>
                    @empty
                        <p class="no-files"><em>Файлы отсутствуют</em></p>
                    @endforelse
                </ul>

                <h3 class="task-heading">Участники:</h3>
                @if ($task->users->isEmpty())
                    <p class="no-tasks"><em>Нет участников</em></p>
                @else
                    <ul class="task-list">
                        @foreach ($task->users as $user)
                            <li class="task-item">{{ $user->name }}</li>
                        @endforeach
                    </ul>
                @endif
                <div class="button-group">
                    <a href="{{ route('dashboard.tasks.edit', $task->id) }}" class="btn btn-edit">Редактировать</a>

                    <form action="{{ route('dashboard.tasks.destroy', $task->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" onclick="return confirm('Вы уверены, что хотите удалить задачу?')">Удалить</button>
                    </form>

                    <form action="{{ route('dashboard.tasks.show', $task->id) }}" method="GET" style="display: inline;">
                        <button type="submit" class="btn btn-view">Просмотр</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="no-projects">Нет задач для вашей компании.</p>
        @endforelse
    </div>
@endsection
