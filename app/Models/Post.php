<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'genre', 'form', 'user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likesCount()
    {
        return $this->likes()->where('is_like', true)->count();
    }

    public function dislikesCount()
    {
        return $this->likes()->where('is_like', false)->count();
    }

    public function scopeFilter($query, array $filters)
    {
        // Поиск по заголовку
        $query->when($filters['search_title'] ?? false, function ($query, $search) {
            $query->where('title', 'like', '%' . $search . '%');
        });

        // Поиск по ID
        $query->when($filters['search_id'] ?? false, function ($query, $id) {
            $query->where('id', $id);
        });

        // Фильтр по жанру
        $query->when($filters['genre'] ?? false, function ($query, $genre) {
            $query->where('genre', $genre);
        });

        // Фильтр по формату
        $query->when($filters['form'] ?? false, function ($query, $form) {
            $query->where('form', $form);
        });

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

    public function getPaginatedContent($page = 1, $charsPerPage = 2000)
    {
        $content = $this->content;
        $totalChars = mb_strlen($content);
        $totalPages = ceil($totalChars / $charsPerPage);

        $start = ($page - 1) * $charsPerPage;
        $pageContent = mb_substr($content, $start, $charsPerPage);

        return [
            'content' => $pageContent,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'has_more_pages' => $page < $totalPages,
            'has_previous_pages' => $page > 1
        ];
    }

    public function isLikedBy($user)
    {
        if (!$user)
            return false;
        return $this->likes()->where('user_id', $user->id)->where('is_like', true)->exists();
    }

    public function isDislikedBy($user)
    {
        if (!$user)
            return false;
        return $this->likes()->where('user_id', $user->id)->where('is_like', false)->exists();
    }
}
