@extends('app')





@section('title')

<title>Login</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])


@endsection



@section('body')

<canvas id="bgCanvas"></canvas>

    <div class="login-card">
        <div class="shield-container">
            <div class="shield-glow"></div>
            <div class="shield">
                <i class="fas fa-lock"></i>
            </div>
        </div>

        <form class="login-form" action="{{ route('sign-verification') }}" method="post">
    @csrf
    <div class="input-wrapper">
        <i class="far fa-user"></i>
        <input type="email" name="email" placeholder="Логин" required value="{{ old('email') }}">
    </div>
    
    <div class="input-wrapper">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Пароль" required>
    </div>

    <button type="submit" class="btn-login">
        <span>Войти</span>
    </button>
</form>
    </div>

@endsection

