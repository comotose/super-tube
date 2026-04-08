@extends('layouts.app')

@section('title', $playlist->name)

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <div class="text-muted small"><a href="{{ route('playlists.index') }}" class="text-decoration-none">&larr; Плейлисты</a></div>
            <h1 class="h4 mb-0">{{ $playlist->name }}</h1>
        </div>
        <form method="POST" action="{{ route('playlists.destroy', $playlist) }}" onsubmit="return confirm('Удалить плейлист?')">
            @csrf
            <button class="btn btn-outline-danger btn-sm" type="submit">Удалить плейлист</button>
        </form>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="fw-semibold mb-2">Добавить видео в плейлист</div>
            <form method="POST" action="{{ route('playlists.addVideo', $playlist) }}" class="row g-2">
                @csrf
                <div class="col-md-9">
                    <select name="video_id" class="form-select" required>
                        <option value="">-- выберите видео --</option>
                        @foreach($availableVideos as $video)
                            <option value="{{ $video->id }}">{{ $video->title }} ({{ $video->user?->name ?? '—' }})</option>
                        @endforeach
                    </select>
                    <div class="form-text">Показываю последние 50 доступных видео (ваши + не забаненных авторов).</div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-dark w-100" type="submit">Добавить</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="fw-semibold mb-2">Видео в плейлисте</div>

            @if($playlist->videos->count() === 0)
                <div class="alert alert-secondary mb-0">В плейлисте пока нет видео.</div>
            @else
                <div class="list-group">
                    @foreach($playlist->videos as $video)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="me-3">
                                    <div class="fw-semibold">
                                        <a class="text-decoration-none" href="{{ route('videos.show', $video) }}">{{ $video->title }}</a>
                                    </div>
                                    <div class="small text-muted">Автор: {{ $video->user?->name ?? '—' }}</div>
                                </div>

                                <form method="POST" action="{{ route('playlists.removeVideo', [$playlist, $video]) }}">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm" type="submit">Убрать</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

