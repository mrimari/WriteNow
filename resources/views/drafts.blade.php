<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/drafts.css') }}?v={{ time() }}">
    <title>Черновики</title>
</head>

<body>
    <div class="background">
        <section class="header_edit">
            <a class="logo_edit" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="Логотип компании">
            </a>
            <a href="{{ url()->previous() }}" class="back">
                🠔 Вернуться
            </a>
        </section>
        <section class="content">
            <div class="header_content">
                <p>Пользователь</p>
                <p>Название</p>
                <p>Дата</p>
                <p>Статус</p>
                <p>Публикация</p>
            </div>
            <hr class="line_content">
            @forelse($drafts as $draft)
                <div class="content_content">
                    <p>{{ $draft->user->name }}</p>
                    <p>{{ $draft->title }}</p>
                    <p>{{ $draft->created_at->format('d.m.Y') }}</p>
                    <p>Черновик</p>
                    <a href="{{ route('posts.edit', $draft) }}" class="btn_content">Редактировать</a>
                </div>
            @empty
                <div class="content_content">
                    <p colspan="5">У вас нет черновиков</p>
                </div>
            @endforelse
        </section>
    </div>
</body>

</html>