<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сайт</title>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    @yield('style')
</head>

<body>
    <div class="background">
        <header class="header">
            <div class="nav">
                <a class="logo_header" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.svg') }}" alt="Логотип компании">
                </a>
                <a href="{{ route('posts') }}" class="nav_link {{ request()->routeIs('posts') ? 'active' : '' }}" id="katalog">Открой</a>
                <a href="{{ route('users') }}" class="nav_link {{ request()->routeIs('users') ? 'active' : '' }}">Творцы</a>
                <a href="" class="nav_link {{ request()->routeIs('about_us') ? 'active' : '' }}">О нас</a>
                @if (Auth::check())
                    <a href="{{ route('profile') }}" class="profile_img">
                        <img src="{{ asset('images/profile.svg') }}" alt="Логотип компании">
                    </a>
                @else
                    <a href="{{ route('auth') }}" class="header_btn">
                        Войти
                    </a>
                @endif

            </div>
        </header>
        @yield('content')
        <footer class="footer">
            <div class="left_line">

            </div>
            <a class="logo_footer" href="">
                <img src="{{ asset('images/logo.svg') }}" alt="Логотип компании">
            </a>
            <div class="right_line">

            </div>
            <div class="pages">
                <a href="">Главная</a>
                <a href="">Произведения</a>
                <a href="">О нас</a>
                <a href="">Творцы</a>
            </div>
            <div class="mid_line">

            </div>
            <div class="politic">
                <a href="{{ route('privacy-policy') }}">Политика конфиденциальности</a>
                <a href="{{ route('terms') }}">Правила и условия</a>
            </div>
            <div class="social">
                <a class="tg" href="">
                    <img src="{{ asset('images/tg.svg') }}" alt="Логотип компании">
                </a>
                <a class="vk" href="">
                    <img src="{{ asset('images/vk.svg') }}" alt="Логотип компании">
                </a>
                <a class="mail" href="">
                    <img src="{{ asset('images/mail.svg') }}" alt="Логотип компании">
                </a>
            </div>
            <p class="©">© 2024 WriteNow. Все права защищены</p>

        </footer>
    </div>
</body>

</html>