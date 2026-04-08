@extends('layouts.app')

@section('title', $video->title)

@section('content')
    <div class="mb-3">
        <a href="{{ route('videos.index') }}" class="text-decoration-none">&larr; Назад к ленте</a>
    </div>

    <div class="card">
        <div class="card-body">
            <h1 class="h4 mb-1">{{ $video->title }}</h1>
            <div class="text-muted small mb-3">
                Автор: {{ $video->user?->name ?? '—' }} |
                Добавлено: {{ $video->created_at?->format('d.m.Y H:i') }}
            </div>

            <video class="w-100" controls preload="metadata">
                <source src="/{{ $video->video_path }}">
                Ваш браузер не поддерживает видео.
            </video>

            @if($video->description)
                <div class="mt-3">
                    <div class="fw-semibold mb-1">Описание</div>
                    <div class="text-muted">{{ $video->description }}</div>
                </div>
            @endif

            @auth
                <hr class="my-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <form method="POST" action="{{ route('reactions.react', $video) }}">
                        @csrf
                        <input type="hidden" name="type" value="like">
                        <button class="btn btn-sm {{ ($myReaction ?? null) === 'like' ? 'btn-success' : 'btn-outline-success' }}" type="submit">
                            Лайк
                        </button>
                    </form>

                    <form method="POST" action="{{ route('reactions.react', $video) }}">
                        @csrf
                        <input type="hidden" name="type" value="dislike">
                        <button class="btn btn-sm {{ ($myReaction ?? null) === 'dislike' ? 'btn-danger' : 'btn-outline-danger' }}" type="submit">
                            Дизлайк
                        </button>
                    </form>

                    <form method="POST" action="{{ route('favorites.toggle', $video) }}">
                        @csrf
                        <button class="btn btn-sm {{ ($isFavorite ?? false) ? 'btn-warning' : 'btn-outline-warning' }}" type="submit">
                            {{ ($isFavorite ?? false) ? 'В Избранном' : 'В Избранное' }}
                        </button>
                    </form>
                </div>
            @endauth

            <hr class="my-3">
            <div class="small text-muted">
                Лайки: {{ $video->likes_count }} |
                Дизлайки: {{ $video->dislikes_count }} |
                Избранное: {{ $video->favorites_count }} |
                Комментарии: {{ $video->comments_count }}
            </div>

            <hr class="my-3">
            <h2 class="h5 mb-3">Комментарии</h2>

            @auth
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="POST" action="{{ route('comments.store', $video) }}">
                            @csrf
                            <div class="mb-2">
                                <textarea class="form-control" name="body" rows="3" placeholder="Ваш комментарий..." required>{{ old('body') }}</textarea>
                            </div>
                            <button class="btn btn-dark btn-sm" type="submit">Отправить</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-secondary">
                    Чтобы оставлять комментарии, нужно <a href="{{ route('login') }}">войти</a>.
                </div>
            @endauth

            @if(($comments ?? collect())->count() === 0)
                <div class="alert alert-secondary">Комментариев пока нет.</div>
            @else
                @foreach($comments as $comment)
                    @include('partials.comment', ['comment' => $comment, 'video' => $video])
                @endforeach
            @endif
        </div>
    </div>
@endsection

