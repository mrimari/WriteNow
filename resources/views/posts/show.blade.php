@extends('layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection
@section('content')
    <div class="work">

        <div class="back-button-container">
            <a href="{{ url()->previous() }}" class="back-button">
                🠔 Вернуться
            </a>
        </div>

        <div class="author-info">
            <p><span class="light">Автор:</span> <a
                    href="{{ route('showUser', $post->user->id) }}">{{ $post->user->name }}</a></p>
            <p><span class="light">id произведения:</span> {{ $post->id }}</p>
            <p><span class="light">Жанр:</span> {{ $post->genre }}</p>
            <p><span class="light">Форма:</span> {{ $post->form }}</p>
            <p><span class="light">Дата публикации:</span> {{ $post->created_at->format('d.m.Y') }}</p>
            <p><span class="light">Дата последнего обновления:</span> {{ $post->updated_at->format('d.m.Y') }}</p>
        </div>

        @if(auth()->check() && auth()->id() === $post->user_id)
            <form class="edit_delete_post" action="{{ route('posts.destroy', $post) }}" method="POST"
                onsubmit="return confirm('Вы уверены, что хотите удалить этот пост?');">
                @csrf
                @method('DELETE')
                <a href="{{ route('posts.edit', $post) }}" class="edit_post">Редактировать</a>
                <button type="submit" class="delete_post">Удалить</button>
            </form>
        @endif

        <h1 class="title_work">{{$post->title}}</h1>

        <!-- Кнопки лайков/дизлайков -->

        <div class="content_work {{ $post->form }}" id="content_work">
            {!! nl2br(e($paginatedContent['content'])) !!}
        </div>

        <!-- Пагинация -->
        <div class="pagination">

            <div class="light pagination-info">
                Страница {{ $paginatedContent['current_page'] }} из {{ $paginatedContent['total_pages'] }}
            </div>

            <div class="pagination-buttons">
                @if($paginatedContent['has_previous_pages'])
                    <a href="{{ route('posts.show', ['post' => $post->id, 'page' => $paginatedContent['current_page'] - 1]) }}"
                        class="pagination-btn">
                        🠔 Предыдущая
                    </a>
                @endif

                @if($paginatedContent['has_more_pages'])
                    <a href="{{ route('posts.show', ['post' => $post->id, 'page' => $paginatedContent['current_page'] + 1]) }}"
                        class="pagination-btn">
                        Следующая 🠖
                    </a>
                @endif
            </div>
        </div>

        <div class="reactions-container">
            <p class="light">Ваша оценка:</p>
            <form action="{{ route('likes.toggle', $post) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="is_like" value="1">
                <button type="submit" class="btn btn-outline-primary reaction-btn">
                    <svg class="reaction-icon {{ $post->isLikedBy(auth()->user()) ? 'active' : '' }}" width="44" height="44"
                        viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M42 14.5668C42 23.3317 30.2569 35.5239 23.3016 41.4942C22.5369 42.1503 21.448 42.1708 20.6644 41.5399C13.705 35.9362 2 24.1923 2 14.5668C2 -1.77728 22 -1.77726 22 12.2319C22 -1.77728 42 -1.77733 42 14.5668Z"
                            stroke="#301B00" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    {{ $post->likesCount() }}
                </button>
            </form>

            <form action="{{ route('likes.toggle', $post) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="is_like" value="0">
                <button type="submit" class="btn btn-outline-danger reaction-btn">
                    <svg fill="none" width="39" height="44" viewBox="0 0 39 44" xmlns="http://www.w3.org/2000/svg"
                        class="reaction-icon {{ $post->isDislikedBy(auth()->user()) ? 'active' : '' }}">
                        <path
                            d="M23.815 2.03072H6.5086C5.49306 2.03072 4.63872 2.79182 4.52188 3.80062L2.22229 23.6556C2.0983 24.7262 2.85056 25.6921 3.89973 25.9385C10.841 27.5685 13.5707 31.7696 14.3636 35.3104C14.8773 37.6046 15.1178 40.3298 17.1691 41.4786C19.635 42.8596 21.6921 41.2627 22.9534 39.4214C23.5994 38.4782 23.6548 37.2795 23.361 36.1747L21.2111 28.0888C20.8736 26.8195 21.8305 25.5749 23.144 25.5749H31.9956C36.7949 25.5749 37.2676 20.224 36.904 17.5485C36.0314 4.27812 27.8144 1.67399 23.815 2.03072Z"
                            stroke="#301B00" stroke-width="3" />
                    </svg>
                    {{ $post->dislikesCount() }}
                </button>
            </form>
        </div>



        <div class="comments-section">
            <p class="light comments-title">Комментарии:</p>

            <!-- Форма добавления комментария -->
            @auth
                <form action="{{ route('comments.store', $post) }}" method="POST" class="comment-form">
                    @csrf
                    <textarea name="content" class="form-control" rows="3" placeholder="Напишите комментарий..."
                        required></textarea>
                    <button type="submit" class="comment-btn">Отправить</button>
                </form>
            @endauth

            <!-- Список комментариев -->
            <div class="comments-list">
                @foreach($post->comments()->with('user')->latest()->get() as $comment)
                    <div class="comment">
                        <div class="comment-header">
                            <img src="{{ asset('storage/avatars/' . ($comment->user->avatar ?? 'default-avatar.svg')) }}"
                                alt="avatar" class="comment-avatar">
                            <div class="comment-info">
                                <a href="{{ route('showUser', $comment->user->id) }}"><span
                                        class="light">{{ $comment->user->name }}</span></a>
                                <small class="text-muted">{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                            </div>
                        </div>
                        <hr class="comment-divider">
                        <div class="comment-content">
                            {{ $comment->content }}
                        </div>
                        @if(Auth::id() === $comment->user_id || Auth::user()->is_admin ?? false)
                            <hr class="comment-divider">
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-comment-btn">Удалить</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @auth
            <div class="report-section">
                <button class="report-btn"
                    onclick="document.getElementById('report-form').style.display='block'">Пожаловаться</button>
                <form id="report-form" action="{{ route('reports.store') }}" method="POST" class="report-form"
                    style="display:none;">
                    @csrf
                    <input type="hidden" name="reportable_type" value="App\Models\Post">
                    <input type="hidden" name="reportable_id" value="{{ $post->id }}">
                    <div class="report-form-group">
                        <label for="reason">Причина жалобы:</label>
                        <input type="text" name="reason" required maxlength="255">
                    </div>
                    <div class="report-form-group">
                        <label for="description">Описание (необязательно):</label>
                        <textarea name="description" maxlength="1000"></textarea>
                    </div>
                    <div class="report-form-buttons">
                        <button type="submit" class="report-submit-btn">Отправить жалобу</button>
                        <button type="button" class="report-cancel-btn"
                            onclick="document.getElementById('report-form').style.display='none'">Отмена</button>
                    </div>
                </form>
            </div>
        @endauth
    </div>

@endsection