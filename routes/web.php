<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\MyVideoController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('videos.index');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.do');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.do');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
Route::get('/videos/{video}', [VideoController::class, 'show'])->name('videos.show');

Route::middleware(['auth', 'not_perma_banned'])->group(function () {
    Route::get('/me/videos', [MyVideoController::class, 'index'])->name('me.videos');
    Route::get('/me/videos/create', [MyVideoController::class, 'create'])->name('me.videos.create');
    Route::post('/me/videos', [MyVideoController::class, 'store'])->name('me.videos.store');
    Route::get('/me/videos/{video}/edit', [MyVideoController::class, 'edit'])->name('me.videos.edit');
    Route::post('/me/videos/{video}', [MyVideoController::class, 'update'])->name('me.videos.update');
    Route::post('/me/videos/{video}/delete', [MyVideoController::class, 'destroy'])->name('me.videos.destroy');

    Route::get('/playlists', [PlaylistController::class, 'index'])->name('playlists.index');
    Route::get('/playlists/create', [PlaylistController::class, 'create'])->name('playlists.create');
    Route::post('/playlists', [PlaylistController::class, 'store'])->name('playlists.store');
    Route::get('/playlists/{playlist}', [PlaylistController::class, 'show'])->name('playlists.show');
    Route::post('/playlists/{playlist}/add-video', [PlaylistController::class, 'addVideo'])->name('playlists.addVideo');
    Route::post('/playlists/{playlist}/remove-video/{video}', [PlaylistController::class, 'removeVideo'])->name('playlists.removeVideo');
    Route::post('/playlists/{playlist}/delete', [PlaylistController::class, 'destroy'])->name('playlists.destroy');

    // дальше будут избранное / админка
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle/{video}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::post('/reactions/{video}', [ReactionController::class, 'react'])->name('reactions.react');

    Route::post('/comments/{video}', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/update/{comment}', [CommentController::class, 'update'])->name('comments.update');

    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
});
