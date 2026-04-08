<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MyVideoController extends Controller
{
    public function index(Request $request)
    {
        $videos = Video::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->paginate(12);

        return view('me.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('me.videos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video' => ['required', 'file', 'mimetypes:video/mp4,video/webm,video/ogg', 'max:51200'],
        ]);

        $file = $request->file('video');
        $name = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();

        $dir = public_path('uploads/videos');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $file->move($dir, $name);

        Video::create([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'video_path' => 'uploads/videos/'.$name,
        ]);

        return redirect()->route('me.videos')->with('status', 'Видео добавлено.');
    }

    public function edit(Request $request, Video $video)
    {
        abort_unless($video->user_id === $request->user()->id, 403);

        return view('me.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        abort_unless($video->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg', 'max:51200'],
        ]);

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $name = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();

            $dir = public_path('uploads/videos');
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $file->move($dir, $name);

            $old = public_path($video->video_path);
            if (is_file($old)) {
                @unlink($old);
            }

            $video->video_path = 'uploads/videos/'.$name;
        }

        $video->title = $data['title'];
        $video->description = $data['description'] ?? null;
        $video->save();

        return redirect()->route('me.videos')->with('status', 'Видео обновлено.');
    }

    public function destroy(Request $request, Video $video)
    {
        abort_unless($video->user_id === $request->user()->id, 403);

        $path = public_path($video->video_path);
        if (is_file($path)) {
            @unlink($path);
        }

        $video->delete();

        return redirect()->route('me.videos')->with('status', 'Видео удалено.');
    }
}

