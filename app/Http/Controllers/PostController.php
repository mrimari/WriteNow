<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * Контроллер для управления постами
 */
class PostController extends Controller
{
    public function index()
    {
        $genres = \App\Models\Category::pluck('name');
        $forms = Post::select('form')->distinct()->pluck('form');

        // Определяем количество элементов на странице в зависимости от устройства
        $perPage = $this->isMobileDevice() ? 6 : 12;

        $posts = Post::with('user')
            ->where('status', 'published')
            ->withCount(['likes as likes_count' => function($query) {
                $query->where('is_like', true);
            }])
            ->withCount(['likes as dislikes_count' => function($query) {
                $query->where('is_like', false);
            }])
            ->filter(request(['search_title', 'search_id', 'genre', 'form', 'filter']))
            ->paginate($perPage);

        return view('posts.posts', [
            'posts' => $posts,
            'genres' => $genres,
            'forms' => $forms
        ]);
    }

    /**
     * Определяет, является ли устройство мобильным
     * 
     * @return bool
     */
    private function isMobileDevice()
    {
        // Сначала проверяем параметр из JavaScript
        if (request()->has('is_mobile')) {
            return request()->input('is_mobile') == '1';
        }
        
        $userAgent = request()->header('User-Agent');
        
        // Проверяем наличие мобильных ключевых слов в User-Agent
        $mobileKeywords = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone',
            'BlackBerry', 'Opera Mini', 'IEMobile'
        ];
        
        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    public function create()
    {
        $categories = Category::all();
        return view('posts.create_post', compact('categories'));
    }

    public function drafts()
    {
        $user = auth()->user();
        $drafts = $user->posts()->where('status', 'draft')->latest()->get();
        return view('drafts', compact('drafts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'genre' => 'required',
            'form' => 'required'
        ]);

        $validated['user_id'] = auth()->id();
        
        // Проверяем значение action из формы
        $action = $request->input('action');
        $validated['status'] = ($action === 'draft') ? 'draft' : 'published';

        $post = Post::create($validated);

        // Обновляем статистику профиля
        $user = auth()->user();
        $user->updateCounts();

        // Перенаправляем на страницу черновиков, если это черновик
        if ($action === 'draft') {
            return redirect()->route('drafts')->with('success', 'Черновик успешно сохранен');
        }

        return redirect('/profile');
    }
    public function show(Post $post)
    {
        // Проверяем, является ли пост черновиком и не является ли текущий пользователь его автором
        if ($post->status === 'draft' && auth()->id() !== $post->user_id) {
            abort(403, 'Нет доступа к черновику');
        }

        $page = request()->get('page', 1);
        $paginatedContent = $post->getPaginatedContent($page);
        
        return view('posts.show', [
            'post' => $post,
            'paginatedContent' => $paginatedContent
        ]);
    }

    /**
     * Получает популярные посты
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getPopularPosts()
    {
        $posts = Post::with('user')
            ->where('status', 'published')
            ->withCount(['likes as likes_count' => function($query) {
                $query->where('is_like', true);
            }])
            ->withCount(['likes as dislikes_count' => function($query) {
                $query->where('is_like', false);
            }])
            ->having('likes_count', '>', 0)
            ->orderByRaw('(likes_count - dislikes_count) DESC')
            ->take(4)
            ->get();

        return $posts;
    }

    public function home()
    {
        $popularPosts = $this->getPopularPosts();
        $usersCount = \App\Models\User::count();
        $postsCount = \App\Models\Post::count();
        return view('home', compact('popularPosts', 'usersCount', 'postsCount'));
    }

    public function edit(Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403, 'Нет доступа');
        }
        $categories = Category::all();
        return view('posts.edit_post', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403, 'Нет доступа');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'genre' => 'required',
            'form' => 'required'
        ]);

        // Сохраняем текущий статус, если это черновик
        if ($post->status === 'draft') {
            $validated['status'] = $request->input('action') === 'publish' ? 'published' : 'draft';
        } else {
            $validated['status'] = $request->input('action') === 'draft' ? 'draft' : 'published';
        }

        $post->update($validated);

        return redirect()->route('posts.show', $post)->with('success', 'Пост успешно обновлён!');
    }

    public function destroy(Post $post)
    {
        if (auth()->id() !== $post->user_id && !auth()->user()->is_admin) {
            abort(403, 'Нет доступа');
        }

        $user = $post->user;
        $post->delete();

        // Если это админ, возвращаемся к списку постов
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.posts')->with('success', 'Пост успешно удален');
        }

        // Если это обычный пользователь, возвращаемся к его профилю
        return redirect()->route('profile')->with('success', 'Пост успешно удален');
    }
}
