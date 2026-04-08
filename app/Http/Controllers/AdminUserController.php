<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->is_admin, 403);

        $users = User::query()
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless($request->user()->is_admin, 403);

        $data = $request->validate([
            'is_admin' => ['nullable', 'in:0,1'],
            'is_shadow_banned' => ['nullable', 'in:0,1'],
            'is_perma_banned' => ['nullable', 'in:0,1'],
        ]);

        // не даём админу забанить самого себя "в ноль"
        if ($request->user()->id === $user->id) {
            $data['is_perma_banned'] = 0;
        }

        $user->is_admin = (bool) ($data['is_admin'] ?? 0);
        $user->is_shadow_banned = (bool) ($data['is_shadow_banned'] ?? 0);
        $user->is_perma_banned = (bool) ($data['is_perma_banned'] ?? 0);
        $user->save();

        return back()->with('status', 'Пользователь обновлён.');
    }
}

