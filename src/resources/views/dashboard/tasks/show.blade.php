@extends('dashboard.layout')

@section('title', 'Просмотр задачи')

@section('content')
    <h1 class="page-title">{{ $task->name }}</h1>

    <p><strong>Описание:</strong> {{ $task->description ?? 'Без описания' }}</p>
    <p><strong>Проект:</strong> {{ $task->project->name ?? 'Без проекта' }}</p>
    <p><strong>Приоритет:</strong> {{ $task->priority }}</p>
    <p><strong>Начало:</strong> {{ $task->start_date }} | <strong>Конец:</strong> {{ $task->end_date }}</p>
    <p><strong>Главный:</strong> {{ $task->leader->name ?? 'Не назначен' }}</p>

    <h3 class="files-heading">Прикреплённые файлы:</h3>
    <ul class="files-list">
        @forelse ($task->files as $file)
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <!-- Ссылка на файл -->
                <a href="{{ url('storage/' . $file->path) }}" download="{{ $file->name }}" style="margin-right: 10px; flex: 1;">
                    {{ $file->name }}
                </a>
                ({{ round($file->size / 1024, 2) }} KB)

                <!-- Кнопка удаления -->
                <form action="/dashboard/tasks/files/{{ $file->id }}" method="POST" style="display: inline; margin-left: 10px;">
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

    <h3 class="task-heading">Участники:</h3>
    @if ($task->users->isEmpty())
        <p><em>Нет участников</em></p>
    @else
        <ul class="task-list">
            @foreach ($task->users as $user)
                <li>{{ $user->name }}</li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('dashboard.tasks.list') }}" class="btn-back">Назад к списку задач</a>
@endsection
