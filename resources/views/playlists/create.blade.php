@extends('layouts.app')

@section('title', 'Создать плейлист')

@section('content')
    <div class="mb-3">
        <a href="{{ route('playlists.index') }}" class="text-decoration-none">&larr; Назад</a>
    </div>

    <div class="card">
        <div class="card-body">
            <h1 class="h4 mb-3">Создать плейлист</h1>

            <form method="POST" action="{{ route('playlists.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Название плейлиста</label>
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}" required>
                </div>

                <button class="btn btn-dark" type="submit">Создать</button>
            </form>
        </div>
    </div>
@endsection

