@extends('layouts.app')

@section('title', 'Плейлисты')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Плейлисты</h1>
        <a class="btn btn-dark btn-sm" href="{{ route('playlists.create') }}">Создать плейлист</a>
    </div>

    @if($playlists->count() === 0)
        <div class="alert alert-secondary">Плейлистов пока нет.</div>
    @else
        <div class="list-group">
            @foreach($playlists as $playlist)
                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                   href="{{ route('playlists.show', $playlist) }}">
                    <div>
                        <div class="fw-semibold">{{ $playlist->name }}</div>
                        <div class="small text-muted">Создан: {{ $playlist->created_at?->format('d.m.Y H:i') }}</div>
                    </div>
                    <span class="badge text-bg-secondary">Открыть</span>
                </a>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $playlists->links() }}
        </div>
    @endif
@endsection

