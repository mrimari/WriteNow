<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'vk_link',
        'tg_link',
        'about',
        'posts_count',
        'follower_count',
        'following_count'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function scopeFilter($query, array $filters)
    {
        // Фильтр по дате
        $query->when($filters['filter'] ?? false, function ($query, $filter) {
            if ($filter === 'new') {
                $query->orderBy('created_at', 'desc');
            } elseif ($filter === 'old') {
                $query->orderBy('created_at', 'asc');
            }
        });

        return $query;
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class);
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'subscriptions', 'following_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'subscriptions', 'follower_id', 'following_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function follow(User $user)
    {
        if (!$this->isFollowing($user)) {
            $this->following()->attach($user->id);
            $user->increment('follower_count');
            $this->increment('following_count');
        }
    }

    public function unfollow(User $user)
    {
        if ($this->isFollowing($user)) {
            $this->following()->detach($user->id);
            $user->decrement('follower_count');
            $this->decrement('following_count');
        }
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function updateCounts()
    {
        $this->update([
            'posts_count' => $this->posts()->count(),
            'follower_count' => $this->followers()->count(),
            'following_count' => $this->following()->count()
        ]);
    }
}
