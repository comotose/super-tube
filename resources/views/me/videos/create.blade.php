@extends('layouts.app')

@section('title', 'Добавить видео')

@section('content')
    <div class="mb-3">
        <a href="{{ route('me.videos') }}" class="text-decoration-none">&larr; Назад</a>
    </div>

    <div class="card">
        <div class="card-body">
            <h1 class="h4 mb-3">Добавить видео</h1>

            <form method="POST" action="{{ route('me.videos.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Название</label>
                    <input class="form-control" type="text" name="title" value="{{ old('title') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Описание</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Файл видео (mp4/webm/ogg)</label>
                    <input class="form-control" type="file" name="video" required>
                    <div class="form-text">Ограничение: до 50 МБ.</div>
                </div>

                <button class="btn btn-dark" type="submit">Сохранить</button>
            </form>
        </div>
    </div>
@endsection

