@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-3">Регистрация</h1>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form method="POST" action="{{ route('register.do') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Имя</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Пароль</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="form-text">Минимум 6 символов.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Повтор пароля</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button class="btn btn-dark w-100" type="submit">Создать аккаунт</button>
                </form>

                <div class="mt-3">
                    Уже есть аккаунт? <a href="{{ route('login') }}">Вход</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection