<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $videosQuery = Video::query()->with('user');

        // Админ видит все видео без фильтров
        if (!$user || !$user->is_admin) {
            $videosQuery->where(function ($q) use ($user) {
                // доступные всем (не забаненные авторы)
                $q->whereHas('user', function ($uq) {
                    $uq->where('is_perma_banned', false)
                        ->where('is_shadow_banned', false);
                });

                // + свои видео (даже если теневой бан)
                if ($user) {
                    $q->orWhere('user_id', $user->id);
                }
            });
        }

        $videos = $videosQuery
            ->orderByDesc('id')
            ->paginate(12);

        return view('videos.index', compact('videos'));
    }

    public function show(Request $request, Video $video)
    {
        $user = $request->user();
        $owner = $video->user()->first();

        $isOwnerOrAdmin = $user && ($user->id === $video->user_id || $user->is_admin);

        if (!$isOwnerOrAdmin) {
            if ($owner && ($owner->is_perma_banned || $owner->is_shadow_banned)) {
                abort(404);
            }
        }

        $video->load('user');

        $comments = $video->comments()
            ->with(['user', 'replies.user'])
            ->whereNull('parent_id')
            ->orderByDesc('id')
            ->get();

        $myReaction = null;
        $isFavorite = false;
        if ($user) {
            $myReaction = $video->reactions()->where('user_id', $user->id)->value('type');
            $isFavorite = $video->favorites()->where('user_id', $user->id)->exists();
        }

        return view('videos.show', compact('video', 'myReaction', 'isFavorite', 'comments'));
    }
}

