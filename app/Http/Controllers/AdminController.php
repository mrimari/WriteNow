<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Services\AchievementService;

class AdminController extends Controller
{
    public function dashboard()
    {
        $usersCount = User::count();
        $postsCount = Post::count();
        $reportsCount = Report::where('status', 'pending')->count();
        
        return view('admin.dashboard', compact('usersCount', 'postsCount', 'reportsCount'));
    }

    public function users()
    {
        $users = User::with('profile')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        $user->is_admin = !$user->is_admin;
        $user->save();
        
        return redirect()->back()->with('success', 'Статус администратора успешно изменен');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Пользователь успешно удален');
    }

    public function reports()
    {
        $reports = Report::with(['user', 'reportable'])->latest()->paginate(20);
        return view('admin.reports.index', compact('reports'));
    }

    public function posts()
    {
        $posts = Post::with('user')->latest()->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    public function deletePost(Post $post)
    {
        $user = $post->user;
        $post->delete();

        // Проверяем достижения после удаления поста
        app(AchievementService::class)->checkAchievements($user);

        return redirect()->route('admin.posts')->with('success', 'Пост успешно удален');
    }

    public function banUser(User $user)
    {
        $user->banned = true;
        $user->save();
        return redirect()->back()->with('success', 'Пользователь забанен');
    }

    public function unbanUser(User $user)
    {
        $user->banned = false;
        $user->save();
        return redirect()->back()->with('success', 'Пользователь разбанен');
    }
}
