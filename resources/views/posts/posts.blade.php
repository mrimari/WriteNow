@extends('layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/posts.css') }}">
@endsection
@section('content')
    <form method="GET" action="{{ route('posts') }}" class="search_form">
        @csrf
        <input value="{{ request('search') }}" class="search_input" type="text" name="search_title" placeholder="Введите название"
            value="">

        <select name="genre" id="" class="genre">
            <option value="">Все жаанры</option>
            @foreach($genres as $genre)
                <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                    {{ $genre }}
                </option>
            @endforeach
        </select>
        <select name="form" id="" class="forma">
            <option value="">Все формы</option>
            @foreach($forms as $form)
                <option value="{{ $form }}" {{ request('form') == $form ? 'selected' : '' }}>
                    {{ $form }}
                </option>
            @endforeach
        </select>
        <input class="id_input" type="text" placeholder="id произведения" value="{{ request('search_id') }}" name="search_id">
        <button class="search_button">Поиск</button>
        <button type="submit" name="filter" value="all" class="all_works">Все</button>
        <button type="submit" name="filter" value="new" class="new_works">Новые</button>
        <button type="submit" name="filter" value="old" class="old_works">Старые</button>
    </form>

    <div class="katalog">
        @foreach($posts as $post)
            <a href="/posts/{{ $post->id }}" class="card">
                <div class="proizv">
                    <h2 class="title_proizv">{{ $post->title }}</h2>
                    <p class="form">{{ $post->form }}</p>
                    <p class="description">*{{ $post->content }}</p>*
                </div>
                <div class="card_footer">
                    <div class="author_photo">
                        <img class="foto" src="{{ asset('storage/avatars/' . ($post->user->avatar ?? 'default-avatar.svg')) }}"
                            alt="Пушкин">
                    </div>
                    <div class="author_info">
                        <span class="name_author">{{ $post->user->name }}</span>
                        <span class="writer">Поэт</span>
                    </div>
                    <div class="likes">
                        <img class="like" src="{{ 'images/like.svg' }}" alt="">
                        <span class="num">{{ $post->likes_count - $post->dislikes_count }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <div class="pagination">
        {{ $posts->appends(request()->query())->links() }}
    </div>


@endsection