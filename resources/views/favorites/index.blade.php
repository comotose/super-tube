@extends('layouts.app')

@section('title', 'Избранное')

@section('content')
    <h1 class="h4 mb-3">Избранное</h1>

    @if($favorites->count() === 0)
        <div class="alert alert-secondary">В избранном пока нет видео.</div>
    @else
        <div class="list-group">
            @foreach($favorites as $fav)
                @php($video = $fav->video)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="me-3">
                            <div class="fw-semibold">
                                <a class="text-decoration-none" href="{{ route('videos.show', $video) }}">{{ $video->title }}</a>
                            </div>
                            <div class="small text-muted">Автор: {{ $video->user?->name ?? '—' }}</div>
                        </div>

                        <form method="POST" action="{{ route('favorites.toggle', $video) }}">
                            @csrf
                            <button class="btn btn-outline-warning btn-sm" type="submit">Убрать</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $favorites->links() }}
        </div>
    @endif
@endsection

