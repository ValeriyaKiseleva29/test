@extends('dashboard.layout')

@section('title', 'Просмотр проекта')

@section('content')
    <h1 class="page-title">{{ $project->name }}</h1>

    <p><strong>Описание:</strong> {{ $project->description ?? 'Без описания' }}</p>
    <p><strong>Начало:</strong> {{ $project->start_date }} | <strong>Конец:</strong> {{ $project->end_date }}</p>
    <p><strong>Главный:</strong> {{ $project->leader->name ?? 'Не назначен' }}</p>

    <h3 class="files-heading">Прикреплённые файлы:</h3>
    <ul class="files-list">
        @forelse ($project->files as $file)
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <a href="{{ url('storage/' . $file->path) }}" download="{{ $file->name }}" style="margin-right: 10px; flex: 1;">
                    {{ $file->name }}
                </a>
                ({{ round($file->size / 1024, 2) }} KB)

                <!-- Кнопка удаления -->
                <form action="{{ route('dashboard.projects.files.destroy', $file->id) }}" method="POST" style="display: inline; margin-left: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete compact-cross" onclick="return confirm('Вы уверены, что хотите удалить файл?')" title="Удалить">
                        ✖
                    </button>
                </form>
            </li>
        @empty
            <p class="no-files"><em>Файлы отсутствуют</em></p>
        @endforelse
    </ul>

    <h3 class="participants-heading">Участники:</h3>
    @if ($project->users->isEmpty())
        <p><em>Нет участников</em></p>
    @else
        <ul class="participants-list">
            @foreach ($project->users as $user)
                <li>{{ $user->name }}</li>
            @endforeach
        </ul>
    @endif

    <h3 class="task-heading">Задачи:</h3>
    @if ($project->tasks->isEmpty())
        <p><em>Нет задач для этого проекта</em></p>
    @else
        <ul class="task-list">
            @foreach ($project->tasks as $task)
                <li>
                    <h4>{{ $task->name }}</h4>
                    <p>{{ $task->description }}</p>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('dashboard.projects.list') }}" class="btn-back">Назад к списку проектов</a>
@endsection

