<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Services\AchievementService;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $usersCount = User::count();
        $postsCount = Post::count();
        $reportsCount = Report::where('status', 'pending')->count();
        
        return view('admin.dashboard', compact('usersCount', 'postsCount', 'reportsCount'));
    }

    public function statistics()
    {
        // Общая статистика
        $totalUsers = User::count();
        $totalPosts = Post::count();
        $totalReports = Report::count();
        
        // Статистика за последние 30 дней
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $newUsers = User::where('created_at', '>=', $thirtyDaysAgo)->count();
        $newPosts = Post::where('created_at', '>=', $thirtyDaysAgo)->count();
        $newReports = Report::where('created_at', '>=', $thirtyDaysAgo)->count();
        
        // Статистика по статусам
        $pendingReports = Report::where('status', 'pending')->count();
        $resolvedReports = Report::where('status', 'resolved')->count();
        $rejectedReports = Report::where('status', 'rejected')->count();
        
        // Статистика по постам
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();
        
        // Статистика пользователей
        $adminUsers = User::where('is_admin', true)->count();
        $bannedUsers = User::where('banned', true)->count();
        $activeUsers = User::where('banned', false)->count();
        
        // Топ авторов по количеству постов
        $topAuthors = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();
        
        // Статистика по дням (последние 7 дней)
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyStats[] = [
                'date' => $date->format('d.m'),
                'users' => User::whereDate('created_at', $date)->count(),
                'posts' => Post::whereDate('created_at', $date)->count(),
                'reports' => Report::whereDate('created_at', $date)->count(),
            ];
        }
        
        return view('admin.statistics', compact(
            'totalUsers', 'totalPosts', 'totalReports',
            'newUsers', 'newPosts', 'newReports',
            'pendingReports', 'resolvedReports', 'rejectedReports',
            'publishedPosts', 'draftPosts',
            'adminUsers', 'bannedUsers', 'activeUsers',
            'topAuthors', 'dailyStats'
        ));
    }

    public function users()
    {
        $users = User::paginate(20);
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
