@extends('../.app')



@section('title')

<title>Admin home</title>

@endsection




@include('admin.inc.header_admin')



@section('body')



<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Выйти из системы</button>
</form>

<style>
    body { background: #0b0e14; color: white; font-family: sans-serif; padding: 20px; }
    .card { background: #151c26; padding: 20px; border-radius: 12px; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #222; }
    input { background: #0b0e14; border: 1px solid #333; color: white; padding: 8px; border-radius: 4px; }
    .btn { background: #00d2ff; color: black; padding: 8px 15px; border-radius: 5px; text-decoration: none; border: none; cursor: pointer; }
</style>

<div class="admin-container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Панель Администратора</h1>
        <span>Админ: {{ Auth::user()->name }}</span>
    </div>

    <div class="stat-card">
        <h3>Всего пользователей: {{ $total }}</h3>
    </div>

    <div class="stat-card">
        <h4>Добавить нового пользователя</h4>
        <form action="{{ route('admin.user.create') }}" method="POST">
            @csrf
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="text" name="name" placeholder="Имя" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <button type="submit" style="background: #00ff88; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">Создать</button>
            </div>
            @if($errors->any()) 
                <p style="color: #ff4d4d; margin-top: 10px;">{{ $errors->first() }}</p> 
            @endif
            @if(session('success'))
                <p style="color: #00ff88; margin-top: 10px;">{{ session('success') }}</p>
            @endif
        </form>
    </div>

    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u) {{-- Здесь мы используем $u, чтобы не путаться --}}
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>
                    <a href="{{ route('admin.user.view', $u->id) }}" class="btn-view">Просмотреть устройства</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection