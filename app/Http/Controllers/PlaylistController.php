<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function index(Request $request)
    {
        $playlists = Playlist::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->paginate(12);

        return view('playlists.index', compact('playlists'));
    }

    public function create()
    {
        return view('playlists.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $playlist = Playlist::create([
            'user_id' => $request->user()->id,
            'name' => $data['name'],
        ]);

        return redirect()->route('playlists.show', $playlist)->with('status', 'Плейлист создан.');
    }

    public function show(Request $request, Playlist $playlist)
    {
        abort_unless($playlist->user_id === $request->user()->id, 403);

        $playlist->load(['videos.user']);

        $availableVideos = Video::query()
            ->with('user')
            ->where(function ($q) use ($request) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('is_perma_banned', false)
                        ->where('is_shadow_banned', false);
                })->orWhere('user_id', $request->user()->id);
            })
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        return view('playlists.show', compact('playlist', 'availableVideos'));
    }

    public function addVideo(Request $request, Playlist $playlist)
    {
        abort_unless($playlist->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'video_id' => ['required', 'integer', 'exists:videos,id'],
        ]);

        $video = Video::with('user')->findOrFail($data['video_id']);

        // нельзя добавлять чужие видео, если автор в бане (теневой/постоянный), кроме своих
        $owner = $video->user;
        if ($video->user_id !== $request->user()->id && $owner && ($owner->is_perma_banned || $owner->is_shadow_banned)) {
            return back()->withErrors(['video_id' => 'Это видео недоступно для добавления.']);
        }

        $playlist->videos()->syncWithoutDetaching([$video->id]);

        return back()->with('status', 'Видео добавлено в плейлист.');
    }

    public function removeVideo(Request $request, Playlist $playlist, Video $video)
    {
        abort_unless($playlist->user_id === $request->user()->id, 403);

        $playlist->videos()->detach($video->id);

        return back()->with('status', 'Видео удалено из плейлиста.');
    }

    public function destroy(Request $request, Playlist $playlist)
    {
        abort_unless($playlist->user_id === $request->user()->id, 403);

        $playlist->delete();

        return redirect()->route('playlists.index')->with('status', 'Плейлист удалён.');
    }
}

