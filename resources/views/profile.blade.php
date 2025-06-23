@extends('layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}?v={{ time() }}">
@endsection
@section('content')
    <div class="profile">
        <img class="profile_png" src="{{ asset('storage/avatars/' . ($user->avatar ?? 'default-avatar.png')) }}" alt="">
        <div class="dropdown">
            <div class="dropdown_btn">
                <div class="pointer"></div>
                <div class="pointer"></div>
                <div class="pointer"></div>
            </div>
            <div class="dropdown_content">
                <a href="{{ route('users.following', Auth::user()) }}">Мои подписки</a>
                <a href="{{ route('edit_profile') }}">Изменить профиль</a>
                @if(Auth::user() && Auth::user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}">Админка</a>
                @endif
                <form action="{{ route('profile.destroy') }}" method="POST"
                    onsubmit="return confirm('Вы уверены, что хотите удалить свой профиль? Это действие нельзя отменить.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Удалить профиль</button>
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button href="">Выйти</button>
                </form>
            </div>
        </div>

        <h3 class="title_profile">{{ $user->name }}</h3>

        <div class="subscription-info">
            <a href="{{ route('users.followers', $user) }}" class="followers-link">
                Подписчики: <span class="followers-count">{{ $user->followers()->count() }}</span>
            </a>
            @if(Auth::check() && Auth::id() !== $user->id)
                @if(Auth::user()->isFollowing($user))
                    <form action="{{ route('users.unfollow', $user) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="unfollow-btn">Отписаться</button>
                    </form>
                @else
                    <form action="{{ route('users.follow', $user) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="follow-btn">Подписаться</button>
                    </form>
                @endif
            @endif
        </div>

        <p class="light">О себе</p>
        <p class="info">{{ $user->about }}</p>
        <p class="light">Соц. сети:</p>
        <p class="info">ВК - {{ $user->vk_link }}, ТГ - {{ $user->tg_link }};</p>
    </div>

    <div class="works">
        <p class="title_works">Работ: <span class="light_num">{{ $posts->total() }}</span></p>
        <a href="{{ route('drafts') }}" class="chernovik_btn">Черновик</a>
        <a href="{{ route('create_post') }}" class="new_btn">Создать</a>
    </div>

    <div class="katalog">
        @foreach($posts as $post)
            <a href="/posts/{{ $post->id }}" class="card">
                <div class="proizv">
                    <h2 class="title_proizv">{{ $post->title }}</h2>
                    <p class="form">{{ $post->form }}</p>
                    <p class="description">*{{ $post->content }}</p>*
                </div>
                <div class="card_footer">
                    <p class="time">{{ $post->created_at->format('d.m.Y') }}</p>
                    <div class="likes">
                        <img class="like" src="{{ asset('images/like.svg') }}" alt="">
                        <span class="num">{{ $post->likes_count - $post->dislikes_count }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    
    <div class="pagination">
        {{ $posts->links('vendor.pagination.custom') }}
    </div>


    <script>
        // JavaScript для открытия/закрытия выпадающего списка
        document.querySelector('.dropdown_btn').addEventListener('click', function () {
            const dropdown = this.closest('.dropdown');
            dropdown.classList.toggle('active');
        });

        // Закрыть выпадающий список при клике вне его
        document.addEventListener('click', function (event) {
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.remove('active');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Делегируем клик по пагинации
            document.body.addEventListener('click', function (e) {
                if (e.target.tagName === 'A' && e.target.closest('.pagination')) {
                    e.preventDefault();
                    let url = e.target.getAttribute('href');
                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.text())
                        .then(html => {
                            // Парсим только .katalog и .pagination из ответа
                            let parser = new DOMParser();
                            let doc = parser.parseFromString(html, 'text/html');
                            let newKatalog = doc.querySelector('.katalog');
                            let newPagination = doc.querySelector('.pagination');
                            if (newKatalog && newPagination) {
                                document.querySelector('.katalog').innerHTML = newKatalog.innerHTML;
                                document.querySelector('.pagination').innerHTML = newPagination.innerHTML;
                                window.scrollTo({ top: document.querySelector('.katalog').offsetTop, behavior: 'smooth' });
                            }
                        });
                }
            });
        });
    </script>
@endsection