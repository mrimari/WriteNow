<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Password;
use Storage;

class AuthController extends Controller
{
    public function registerForm()
    {
        return view('reg');
    }

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'captcha' => 'required|string|size:6',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->captcha !== session('captcha')) {
                $validator->errors()->add('captcha', 'Неверный код капчи.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => 'default-avatar.png'
        ]);

        Auth::login($user);

        return redirect('/profile');
    }

    public function loginForm()
    {
        return view('auth');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'captcha' => 'required|string|size:6',
        ]);

        if ($request->captcha !== session('captcha')) {
            return back()->withErrors([
                'captcha' => 'Неверный код капчи.',
            ])->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            if ($user->banned) {
                auth()->logout();
                return redirect()->back()->withErrors(['email' => 'Ваш аккаунт забанен. Обратитесь к администрации.']);
            }
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль.',
        ])->withInput();
    }

    public function show()
    {
        $user = Auth::user();
        $posts = $user->posts()
            ->where('status', 'published')
            ->with('user')
            ->withCount(['likes as likes_count' => function($query) {
                $query->where('is_like', true);
            }])
            ->withCount(['likes as dislikes_count' => function($query) {
                $query->where('is_like', false);
            }])
            ->latest()
            ->paginate(10);
        return view('profile', compact('user', 'posts'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        // Удаляем все посты пользователя
        $user->posts()->delete();
        
        // Удаляем все лайки пользователя
        $user->likes()->delete();
        
        // Удаляем все комментарии пользователя
        $user->comments()->delete();
        
        // Удаляем аватар, если он существует
        if ($user->avatar && $user->avatar !== 'default-avatar.svg') {
            Storage::delete('public/avatars/' . $user->avatar);
        }
        
        // Удаляем самого пользователя
        $user->delete();
        
        // Выходим из системы
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Ваш профиль был успешно удален');
    }

    public function edit()
    {
        return view('edit_profile');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => ['nullable', 'image', 'mimes:svg,jpeg,png,jpg,gif'],
            'vk_link' => ['nullable', 'string', 'max:255'],
            'tg_link' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:500'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable'],
        ]);

        // Обновление аватара
        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар, если он не дефолтный
            if ($user->avatar && $user->avatar !== 'default-avatar.png' && $user->avatar !== 'default-avatar.svg') {
                Storage::delete('public/avatars/' . $user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = basename($avatarPath);
        }

        // Обновление пароля, если предоставлен
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->name = $request->name;
        $user->vk_link = $request->vk_link;
        $user->tg_link = $request->tg_link;
        $user->about = $request->about;
        $user->save();

        return redirect('/profile');
    }
    public function index()
    {
        $query = User::query();

        $users = $query
            ->filter(request(['filter']))
            ->paginate(10);

        return view('users', [
            'users' => $users
        ]);
    }

    public function showUser(User $user)
    {
        $posts = $user->posts()
            ->where('status', 'published')
            ->with('user')
            ->withCount(['likes as likes_count' => function($query) {
                $query->where('is_like', true);
            }])
            ->withCount(['likes as dislikes_count' => function($query) {
                $query->where('is_like', false);
            }])
            ->latest()
            ->paginate(10);
        return view('user', ['user' => $user, 'posts' => $posts]);
    }
}
