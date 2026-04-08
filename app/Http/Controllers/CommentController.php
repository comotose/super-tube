<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Video $video)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
        ]);

        if (!empty($data['parent_id'])) {
            $parent = Comment::where('id', $data['parent_id'])->where('video_id', $video->id)->first();
            if (!$parent) {
                return back()->withErrors(['parent_id' => 'Некорректный комментарий для ответа.']);
            }
        }

        Comment::create([
            'video_id' => $video->id,
            'user_id' => $request->user()->id,
            'parent_id' => $data['parent_id'] ?? null,
            'body' => $data['body'],
        ]);

        $video->increment('comments_count');

        return back()->with('status', 'Комментарий добавлен.');
    }

    public function update(Request $request, Comment $comment)
    {
        abort_unless($comment->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $comment->body = $data['body'];
        $comment->edited_at = now();
        $comment->save();

        return back()->with('status', 'Комментарий обновлён.');
    }
}

