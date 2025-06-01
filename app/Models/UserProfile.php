<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'avatar',
        'bio',
        'location',
        'website',
        'social_links',
        'post_count',
        'comment_count',
        'like_count',
        'follower_count',
        'following_count'
    ];

    protected $casts = [
        'social_links' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateCounts()
    {
        $this->update([
            'post_count' => $this->user->posts()->count(),
            'comment_count' => $this->user->comments()->count(),
            'like_count' => $this->user->likes()->count(),
            'follower_count' => $this->user->followers()->count(),
            'following_count' => $this->user->following()->count()
        ]);
    }
} 