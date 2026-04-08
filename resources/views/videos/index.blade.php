@extends('layouts.app')

@section('title', 'Видео')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Видео</h1>
        @auth
            <a class="btn btn-dark btn-sm" href="{{ route('me.videos.create') }}">Добавить видео</a>
        @endauth
    </div>

    @if($videos->count() === 0)
        <div class="alert alert-secondary">Пока нет видео.</div>
    @else
        <div class="row g-3">
            @foreach($videos as $video)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="h6 mb-1">
                                <a href="{{ route('videos.show', $video) }}" class="text-decoration-none">
                                    {{ $video->title }}
                                </a>
                            </h2>
                            <div class="text-muted small mb-2">
                                Автор: {{ $video->user?->name ?? '—' }}
                            </div>
                            <div class="small text-muted">
                                Лайки: {{ $video->likes_count }} |
                                Дизлайки: {{ $video->dislikes_count }} |
                                Избранное: {{ $video->favorites_count }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $videos->links() }}
        </div>
    @endif
@endsection

