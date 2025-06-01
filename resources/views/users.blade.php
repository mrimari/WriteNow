@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ asset('css/authors.css') }}">
@endsection
@section('content')
    <form action="" class="search_form">
        <input class="search_input" type="text" name="search" placeholder="Введите название" value="">
        <button class="search_button">Поиск</button>
        <button type="submit" name="filter" class="all_works">Все</button>
        <button type="submit" name="filter" class="new_works">Новые</button>
    </form>
    <section class="authors">
        @foreach ($users as $user)
        <div class="author">
            <img class="author_img" src="{{ asset('storage/avatars/' . ($user->avatar ?? 'default-avatar.svg')) }}" alt="">
            <h2 class="title">{{ $user->name }}</h2>
            <a href="{{ route('showUser', ['user' => $user->id]) }}" class="author_link">Профиль</a>
        </div>
        @endforeach
    </section>
    
@endsection