@extends('layouts.app')

@section('title', 'Редактировать видео')

@section('content')
    <div class="mb-3">
        <a href="{{ route('me.videos') }}" class="text-decoration-none">&larr; Назад</a>
    </div>

    <div class="card">
        <div class="card-body">
            <h1 class="h4 mb-3">Редактировать видео</h1>

            <form method="POST" action="{{ route('me.videos.update', $video) }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Название</label>
                    <input class="form-control" type="text" name="title" value="{{ old('title', $video->title) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Описание</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description', $video->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Заменить файл (не обязательно)</label>
                    <input class="form-control" type="file" name="video">
                </div>

                <button class="btn btn-dark" type="submit">Сохранить</button>
            </form>
        </div>
    </div>
@endsection

