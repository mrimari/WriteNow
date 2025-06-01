<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function follow(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Вы не можете подписаться на самого себя');
        }

        auth()->user()->follow($user);
        return back()->with('success', 'Вы успешно подписались на пользователя');
    }

    public function unfollow(User $user)
    {
        auth()->user()->unfollow($user);

        return back()->with('success', 'Вы отписались от пользователя');
    }

    public function followers(User $user)
    {
        $followers = $user->followers()->paginate(20);
        return view('users.followers', compact('user', 'followers'));
    }

    public function following(User $user)
    {
        $following = $user->following()->paginate(20);
        return view('users.following', compact('user', 'following'));
    }

    public function index()
    {
        $subscription = Auth::user()->subscription;
        return view('subscriptions.index', compact('subscription'));
    }

    public function create()
    {
        return view('subscriptions.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Здесь будет логика создания подписки через Stripe
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'status' => 'active',
            'trial_ends_at' => now()->addDays(14),
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Подписка успешно создана!');
    }

    public function cancel()
    {
        $subscription = Auth::user()->subscription;
        
        if ($subscription) {
            $subscription->update([
                'ends_at' => now(),
                'status' => 'cancelled'
            ]);
        }

        return redirect()->route('subscriptions.index')
            ->with('success', 'Подписка успешно отменена!');
    }
} 