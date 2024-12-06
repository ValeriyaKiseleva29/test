@extends('dashboard.layout')

@section('title', 'Главная админка')

@section('content')
    <h1>Компания {{ $companyName }} успешно зарегистрирована!</h1>
    <h4>Рады видеть Вас в нашей системе управления проектами</h4>
    <p class="stats">
        Проектов: {{ $projectCount }} | Пользователей: {{ $userCount }} | Задач: {{ $taskCount }}
    </p>
    <p>Выберите действие в меню, чтобы начать работу.</p>
@endsection
