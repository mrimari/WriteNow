<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostInteractionController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TextCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'home'])->name('home');

// Публичные страницы
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Группа гостевых роутов
Route::middleware('guest')->group(function () {
    Route::get('/reg', [AuthController::class, 'registerForm'])->name('reg');
    Route::post('/reg', [AuthController::class, 'register']);
    Route::get('/auth', [AuthController::class, 'loginForm'])->name('auth');
    Route::post('/auth', [AuthController::class, 'login']);
    Route::get('/captcha/generate', [CaptchaController::class, 'generate'])->name('captcha.generate');
});

// Защищённые роуты
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'show'])->name('profile');
    Route::delete('/profile', [AuthController::class, 'destroy'])->name('profile.destroy');

    Route::get('/edit_profile', [AuthController::class, 'edit'])->name('edit_profile');
    Route::put('/edit_profile', [AuthController::class, 'update']);

    Route::get('/create_post', [PostController::class, 'create'])->name('create_post');
    Route::post('/create_post', [PostController::class, 'store']);

    Route::get('/posts', [PostController::class, 'index'])->name('posts');

    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::get('/users', [AuthController::class,'index'])->name('users');

    Route::get('/users/{user}', [AuthController::class, 'showUser'])->name('showUser');

    // Маршруты для комментариев и лайков
    Route::post('/posts/{post}/comments', [PostInteractionController::class, 'addComment'])->name('comments.store');
    Route::delete('/comments/{comment}', [PostInteractionController::class, 'deleteComment'])->name('comments.destroy');
    Route::post('/posts/{post}/like', [PostInteractionController::class, 'toggleLike'])->name('likes.toggle');

    // Маршруты для подписок
    Route::post('/users/{user}/follow', [SubscriptionController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{user}/unfollow', [SubscriptionController::class, 'unfollow'])->name('users.unfollow');
    Route::get('/users/{user}/followers', [SubscriptionController::class, 'followers'])->name('users.followers');
    Route::get('/users/{user}/following', [SubscriptionController::class, 'following'])->name('users.following');
    // Маршруты для жалоб
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/resolve', [ReportController::class, 'resolve'])->name('reports.resolve');
    Route::post('/reports/{report}/reject', [ReportController::class, 'reject'])->name('reports.reject');

    // Маршруты для администратора
    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
        Route::get('/posts', [AdminController::class, 'posts'])->name('admin.posts');
        Route::delete('/posts/{post}', [AdminController::class, 'deletePost'])->name('admin.posts.delete');
        Route::post('/users/{user}/ban', [AdminController::class, 'banUser'])->name('admin.users.ban');
        Route::post('/users/{user}/unban', [AdminController::class, 'unbanUser'])->name('admin.users.unban');
    });

    // Маршруты для подписок
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscription/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscription', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');

    Route::get('/drafts', [PostController::class, 'drafts'])->name('drafts');

    // Маршрут для проверки текста
    Route::post('/text/check', [TextCheckController::class, 'check'])->name('text.check');
});