@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ time() }}">
@endsection
@section('content')
        <section class="container_1">
            <p class="block1_1">
                Платформа вашего <span class="hightlight">творчества</span>
            </p>
            <p class="block2_1">
                Пространство для вдохновения и творчества
            </p>
            <a href="{{ route('posts') }}" class="block3_1 button">Читать</a>
            <a href="{{ route('create_post') }}" class="block4_1 button">Творить</a>
        </section>
        <section class="container_2">
            <div class="offer">
                <h1>Мы <span class="hightlight">вам</span> предлагаем</h1>
                <div class="block1_2">
                    <img class="icon" src="{{ asset('images/prodvizh.svg') }}" alt="">
                    <p class="title">Продвижение</p>
                    <p>Узнаваемость автора и ажиотаж вокруг его произведений</p>
                </div>
                <div class="block2_2">
                    <img class="icon" src="{{ asset('images/support.svg') }}" alt="">
                    <p class="title">Круглосоточная поддержка</p>
                    <p>Обращайтесь в любое время. Ответ от администраторов не заставит долго ждать</p>
                </div>
                <div class="block3_2">
                    <img class="icon" src="{{ asset('images/formats.svg') }}" alt="">
                    <p class="title">Свобода форматов и жанров</p>
                    <p>Публикуйте свои произведения в любых жанрах и в любых размерах</p>
                </div>
            </div>
        </section>
        <section class="container_3">
            <h1>Популярные <span class="hightlight">произведения</span></h1>
            <div class="popular">
                @foreach($popularPosts as $post)
                    <div class="card">
                        <div class="proizv">
                            <h2 class="title">{{ $post->title }}</h2>
                            <p class="form">{{ $post->form }}</p>
                            <p class="description">*{{ $post->content }}</p>*
                        </div>
                        <div class="card_footer">
                            <a href="{{ route('showUser', ['user' => $post->user->id]) }}">
                                <img class="foto" src="{{ asset('storage/avatars/' . ($post->user->avatar ?? 'default-avatar.svg')) }}" alt="{{ $post->user->name }}">
                            </a>
                            <div class="author_info">
                                <a href="{{ route('showUser', ['user' => $post->user->id]) }}" class="name_author">{{ $post->user->name }}</a>
                            </div>
                            <div class="likes">
                                <img class="like" src="{{ asset('images/like.svg') }}" alt="">
                                <span class="num">{{ $post->likes_count - $post->dislikes_count }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        <section class="container_4">
            <h1><span class="hightlight">Статистика</span> платформы</h1>
            <div class="statistic">
                <div class="users">
                    <p class="statistic_users">{{ $usersCount }}</p>
                    <img src="{{ asset('images/Group.svg') }}" alt="" class="users_svg">
                </div>
                <div class="svg_statistic">
                    <img src="{{ asset('images/statistic.svg') }}" alt="тут должна быть иконка">
                </div>
                <div class="works">
                    <p class="statistic_works">{{ $postsCount }}</p>
                    <img src="{{ asset('images/Group2.svg') }}" alt="" class="works_svg">
                </div>
            </div>
        </section>
@endsection