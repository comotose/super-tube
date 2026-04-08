@extends('layouts.app')

@section('title', 'Мои видео')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Мои видео</h1>
        <a class="btn btn-dark btn-sm" href="{{ route('me.videos.create') }}">Добавить</a>
    </div>

    @if($videos->count() === 0)
        <div class="alert alert-secondary">У вас пока нет видео.</div>
    @else
        <div class="list-group">
            @foreach($videos as $video)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="me-3">
                            <div class="fw-semibold">
                                <a class="text-decoration-none" href="{{ route('videos.show', $video) }}">{{ $video->title }}</a>
                            </div>
                            <div class="small text-muted">
                                {{ $video->created_at?->format('d.m.Y H:i') }}
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('me.videos.edit', $video) }}">Редактировать</a>
                            <form method="POST" action="{{ route('me.videos.destroy', $video) }}" onsubmit="return confirm('Удалить видео?')">
                                @csrf
                                <button class="btn btn-outline-danger btn-sm" type="submit">Удалить</button>
                            </form>
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

