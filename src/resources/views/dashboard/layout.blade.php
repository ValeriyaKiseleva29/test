<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Админка')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="dashboard-page">
<div class="sidebar">
    <h2>Меню</h2>
    <ul>
        <li><a href="{{ route('dashboard.index') }}">Главная</a></li>
        <li><a href="{{ route('dashboard.users.create') }}">Добавить пользователя</a></li>
        <li><a href="{{ route('dashboard.users.list') }}">Все пользователи</a></li>
        <li><a href="{{ route('dashboard.projects.create') }}">Добавить проект</a></li>
        <li><a href="{{ route('dashboard.projects.list') }}">Список проектов</a></li>
        <li><a href="{{ route('dashboard.tasks.create') }}">Добавить задачу</a></li>
        <li><a href="{{ route('dashboard.tasks.list') }}">Список всех задач</a></li>
        <li>
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Вы действительно хотите выйти?')">
                @csrf
                <button type="submit" class="logout-button">Выход</button>
            </form>
        </li>
        <form action="{{ route('dashboard.company.destroy', auth()->user()->id) }}" method="POST" id="delete-company-form">
            @csrf
            @method('DELETE')
            <button type="button" onclick="confirmDeletion()">Удалить компанию</button>
        </form>
    </ul>
</div>
<div class="navbar">
    <div>Система управления проектами</div>
</div>
<div class="content">
    @yield('content')
</div>
</body>


<script>
    function confirmDeletion() {
        if (confirm('Вы действительно хотите удалить компанию? Это действие нельзя отменить.')) {
            document.getElementById('delete-company-form').submit();
        }
    }
</script>
</html>
