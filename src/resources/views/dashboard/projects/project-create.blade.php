@extends('dashboard.layout')

@section('title', 'Добавить проект')

@section('content')
    <div class="project-page">
        <form action="{{ route('dashboard.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h1>Добавить проект</h1>

            <!-- Уведомление об успехе -->
            @if(session('success'))
                <script>
                    alert('{{ session('success') }}');
                </script>
            @endif
            <!-- Название проекта -->
            <div>
                <label for="name">Название проекта:</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
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
                    placeholder="Краткое описание проекта">{{ old('description') }}</textarea>
                @error('description')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Начало проекта -->
            <div>
                <label for="start_date">Начало проекта:</label>
                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    value="{{ old('start_date') }}"
                >
                @error('start_date')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Конец проекта -->
            <div>
                <label for="end_date">Конец проекта:</label>
                <input
                    type="date"
                    id="end_date"
                    name="end_date"
                    value="{{ old('end_date') }}"
                >
                @error('end_date')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Участники -->
            <div>
                <label for="participants">Участники:</label>
                <select id="participants" name="participants[]" multiple>
                    <option value="">-- Выберите участников --</option>
                    @foreach($users as $user)
                        <option
                            value="{{ $user->id }}"
                            @if(is_array(old('participants')) && in_array($user->id, old('participants'))) selected @endif
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
                    @foreach($users as $user)
                        <option
                            value="{{ $user->id }}"
                            @if(old('leader') == $user->id) selected @endif
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
            <button type="submit">Создать</button>
        </form>
    </div>
@endsection
