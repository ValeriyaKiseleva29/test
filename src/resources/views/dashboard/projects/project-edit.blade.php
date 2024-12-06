@extends('dashboard.layout')

@section('title', 'Редактировать проект')

@section('content')
    <div class="project-page">
        <form action="{{ route('dashboard.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')
            <h1>Редактировать проект</h1>

            <div>
                <label for="name">Название проекта:</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $project->name) }}"
                >
                @error('name')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Описание проекта -->
            <div>
                <label for="description">Описание проекта:</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    cols="80"
                    maxlength="200"
                >{{ old('description', $project->description) }}</textarea>
                @error('description')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Дата начала проекта -->
            <div>
                <label for="start_date">Начало проекта:</label>
                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    value="{{ old('start_date', $project->start_date) }}"
                >
                @error('start_date')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Дата завершения проекта -->
            <div>
                <label for="end_date">Конец проекта:</label>
                <input
                    type="date"
                    id="end_date"
                    name="end_date"
                    value="{{ old('end_date', $project->end_date) }}"
                >
                @error('end_date')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Участники проекта -->
            <div>
                <label for="participants">Участники:</label>
                <select id="participants" name="participants[]" multiple>
                    @foreach ($users as $user)
                        <option
                            value="{{ $user->id }}"
                            {{ in_array($user->id, old('participants', $project->users->pluck('id')->toArray())) ? 'selected' : '' }}
                        >
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('participants')
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

            <!-- Назначить главным -->
            <div>
                <label for="leader">Назначить главным:</label>
                <select id="leader" name="leader">
                    <option value="">-- Не назначать --</option>
                    @foreach ($users as $user)
                        <option
                            value="{{ $user->id }}"
                            {{ old('leader', $project->leader_id) == $user->id ? 'selected' : '' }}
                        >
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('leader')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Кнопка отправки -->
            <button type="submit">Сохранить изменения</button>
        </form>
    </div>
@endsection
