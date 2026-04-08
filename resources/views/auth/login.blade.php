@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h1 class="h4 mb-3">Вход</h1>

                    <form method="POST" action="{{ route('login.do') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Пароль</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                            <label class="form-check-label" for="remember">Запомнить меня</label>
                        </div>

                        <button class="btn btn-dark w-100" type="submit">Войти</button>
                    </form>

                    <div class="mt-3">
                        Нет аккаунта? <a href="{{ route('register') }}">Регистрация</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

