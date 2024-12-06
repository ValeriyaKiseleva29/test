@extends('dashboard.layout')

@section('title', 'Редактировать задачу')

@section('content')
    <div class="project-page">
        <form action="{{ route('dashboard.tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h1>Редактировать задачу</h1>

            <div>
                <label for="name">Название задачи:</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $task->name) }}"
                >
                @error('name')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Описание задачи -->
            <div>
                <label for="description">Описание задачи:</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    cols="80"
                    maxlength="200"
                >{{ old('description', $task->description) }}</textarea>
                @error('description')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Начало задачи -->
            <div>
                <label for="start_date">Начало задачи:</label>
                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    value="{{ old('start_date', $task->start_date) }}"
                >
                @error('start_date')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Конец задачи -->
            <div>
                <label for="end_date">Конец задачи:</label>
                <input
                    type="date"
                    id="end_date"
                    name="end_date"
                    value="{{ old('end_date', $task->end_date) }}"
                >
                @error('end_date')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Приоритет -->
            <div>
                <label for="priority">Приоритет:</label>
                <select id="priority" name="priority">
                    <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>Срочная</option>
                    <option value="non-urgent" {{ old('priority', $task->priority) == 'non-urgent' ? 'selected' : '' }}>Несрочная</option>
                </select>
                @error('priority')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Проект -->
            <div>
                <label for="project_id">Проект:</label>
                <select id="project_id" name="project_id">
                    @foreach($projects as $project)
                        <option
                            value="{{ $project->id }}"
                            {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}
                        >
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Участники -->
            <div>
                <label for="participants">Участники:</label>
                <select id="participants" name="participants[]" multiple>
                    @foreach($users as $user)
                        <option
                            value="{{ $user->id }}"
                            {{ in_array($user->id, old('participants', $task->users->pluck('id')->toArray())) ? 'selected' : '' }}
                        >
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('participants')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Назначить главным -->
            <div>
                <label for="leader_id">Назначить главным:</label>
                <select id="leader_id" name="leader_id">
                    <option value="">-- Не назначать --</option>
                    @foreach($users as $user)
                        <option
                            value="{{ $user->id }}"
                            {{ old('leader_id', $task->leader_id) == $user->id ? 'selected' : '' }}
                        >
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('leader_id')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Прикрепить файл -->
            <div>
                <label for="file">Прикрепить файл:</label>
                <input
                    type="file"
                    id="file"
                    name="file"
                >
                @error('file')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Кнопка отправки -->
            <button type="submit">Сохранить изменения</button>
        </form>
    </div>
@endsection
