@extends('dashboard.layout')

@section('title', 'Список проектов')

@section('content')
    <h1 class="page-title">Список проектов</h1>

    <div class="projects-grid">
        @forelse ($projects as $project)
            <div class="project-card">
                <h2 class="project-title">{{ $project->name }}</h2>
                <p class="project-description">{{ $project->description }}</p>
                <p class="project-dates">
                    <strong>Начало:</strong> {{ $project->start_date }} |
                    <strong>Конец:</strong> {{ $project->end_date }}
                </p>

                <p class="project-leader">
                    <strong>Главный:</strong>
                    {{ $project->leader->name ?? 'Не назначен' }}
                </p>
                <h3 class="participants-heading">Участники:</h3>
                <ul class="participants-list">
                    @forelse ($project->users as $user)
                        <li>{{ $user->name }}</li>
                    @empty
                        <p class="no-users"><em>Нет участников</em></p>
                    @endforelse
                </ul>

                <h3 class="task-heading">Задачи:</h3>
                @if ($project->tasks->isEmpty())
                    <p class="no-tasks"><em>Нет задач для этого проекта.</em></p>
                @else
                    <ul class="task-list">
                        @foreach ($project->tasks as $task)
                            <li class="task-item">
                                <h4 class="task-title">{{ $task->name }}</h4>
                                <p class="task-description">{{ $task->description }}</p>
                                <p class="task-priority"><strong>Приоритет:</strong> {{ $task->priority }}</p>
                                <p class="task-users">
                                    <strong>Участники:</strong>
                                    @if ($task->users->isEmpty())
                                        <em>Нет участников</em>
                                    @else
                                        {{ $task->users->pluck('name')->join(', ') }}
                                    @endif
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <h3 class="files-heading">Прикреплённые файлы:</h3>
                <ul class="files-list">
                    @forelse ($project->files as $file)
                        <li>
                            <a href="{{ url('storage/' . $file->path) }}" download="{{ $file->name }}">
                                {{ $file->name }}
                            </a> ({{ round($file->size / 1024, 2) }} KB)
                        </li>
                    @empty
                        <p class="no-files"><em>Файлы отсутствуют</em></p>
                    @endforelse
                </ul>
                <div class="actions">
                    <form action="{{ route('dashboard.projects.show', ['id' => $project->id]) }}" method="GET" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-view">Просмотреть</button>
                    </form>
                    <form action="{{ route('dashboard.projects.destroy', $project->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Вы уверены, что хотите удалить проект?')">Удалить</button>
                    </form>
                    <a href="{{ route('dashboard.projects.edit', $project->id) }}" class="btn-edit">Редактировать</a>
                </div>

            </div>

        @empty
            <p class="no-projects">Проекты отсутствуют.</p>
        @endforelse
    </div>
@endsection
