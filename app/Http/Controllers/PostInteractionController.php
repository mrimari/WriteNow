<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostInteractionController extends Controller
{
    public function addComment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        return back()->with('success', 'Комментарий успешно добавлен');
    }

    public function toggleLike(Request $request, Post $post)
    {
        $like = $post->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            if ($like->is_like === $request->is_like) {
                $like->delete();
                return back()->with('success', 'Реакция удалена');
            } else {
                $like->update(['is_like' => $request->is_like]);
                return back()->with('success', 'Реакция изменена');
            }
        }

        $post->likes()->create([
            'user_id' => Auth::id(),
            'is_like' => $request->is_like
        ]);

        return back()->with('success', 'Реакция добавлена');
    }

    public function deleteComment(Comment $comment)
    {
        if (Auth::id() === $comment->user_id || Auth::user()->is_admin) {
            $user = $comment->user;
            $comment->delete();

            return back()->with('success', 'Комментарий удален');
        }

        return back()->with('error', 'У вас нет прав для удаления этого комментария');
    }
} 