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
            if ($user->profile) {
            $user->profile->increment('follower_count');
            }
            if ($this->profile) {
            $this->profile->increment('following_count');
            }
        }
    }

    public function unfollow(User $user)
    {
        if ($this->isFollowing($user)) {
            $this->following()->detach($user->id);
            if ($user->profile) {
            $user->profile->decrement('follower_count');
            }
            if ($this->profile) {
            $this->profile->decrement('following_count');
            }
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
}
