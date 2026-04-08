<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoReaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function react(Request $request, Video $video)
    {
        $data = $request->validate([
            'type' => ['required', 'in:like,dislike'],
        ]);

        $user = $request->user();
        $type = $data['type'];

        $existing = VideoReaction::where('user_id', $user->id)->where('video_id', $video->id)->first();

        if (!$existing) {
            VideoReaction::create([
                'user_id' => $user->id,
                'video_id' => $video->id,
                'type' => $type,
            ]);

            $video->increment($type === 'like' ? 'likes_count' : 'dislikes_count');

            return back()->with('status', $type === 'like' ? 'Лайк поставлен.' : 'Дизлайк поставлен.');
        }

        if ($existing->type === $type) {
            // повторное нажатие снимает реакцию
            $existing->delete();
            $video->decrement($type === 'like' ? 'likes_count' : 'dislikes_count');

            return back()->with('status', $type === 'like' ? 'Лайк убран.' : 'Дизлайк убран.');
        }

        // смена реакции
        $old = $existing->type;
        $existing->type = $type;
        $existing->save();

        $video->decrement($old === 'like' ? 'likes_count' : 'dislikes_count');
        $video->increment($type === 'like' ? 'likes_count' : 'dislikes_count');

        return back()->with('status', 'Реакция обновлена.');
    }
}

