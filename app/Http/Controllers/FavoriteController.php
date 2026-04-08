<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Video;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = Favorite::query()
            ->where('user_id', $request->user()->id)
            ->with(['video.user'])
            ->orderByDesc('id')
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Request $request, Video $video)
    {
        $user = $request->user();

        $fav = Favorite::where('user_id', $user->id)->where('video_id', $video->id)->first();

        if ($fav) {
            $fav->delete();
            $video->decrement('favorites_count');
            return back()->with('status', 'Удалено из Избранного.');
        }

        Favorite::create(['user_id' => $user->id, 'video_id' => $video->id]);
        $video->increment('favorites_count');

        return back()->with('status', 'Добавлено в Избранное.');
    }
}

