<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сайт</title>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}?v={{ filemtime(public_path('css/global.css')) }}">
    @if(request()->routeIs('admin.*'))
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
    @endif
    @if(Auth::check() && Auth::user()->is_admin)
        <meta name="current-admin-id" content="{{ Auth::id() }}">
    @endif
    @yield('style')
</head>

<body>
    <div class="background">
        <header class="header">
            <div class="nav">
                <a class="logo_header" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.svg') }}" alt="Логотип компании">
                </a>
                
                <!-- Десктопное меню -->
                <div class="desktop-menu">
                    <a href="{{ route('posts') }}" class="nav_link {{ request()->routeIs('posts') ? 'active' : '' }}" id="katalog">Произведения</a>
                    <a href="{{ route('users') }}" class="nav_link {{ request()->routeIs('users') ? 'active' : '' }}">Творцы</a>
                    <a href="{{ route('about') }}" class="nav_link {{ request()->routeIs('about') ? 'active' : '' }}">О нас</a>
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

                <!-- Бургер кнопка для мобильного меню -->
                <div class="burger-menu">
                    <button class="burger-btn" id="burgerBtn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>

            <!-- Мобильное меню -->
            <div class="mobile-menu" id="mobileMenu">
                <div class="mobile-menu-content">
                    <!-- Кнопка закрытия -->
                    <button class="mobile-close-btn" id="mobileCloseBtn">
                        <span></span>
                        <span></span>
                    </button>
                    <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Главная</a>
                    <a href="{{ route('posts') }}" class="mobile-nav-link {{ request()->routeIs('posts') ? 'active' : '' }}">Открой</a>
                    <a href="{{ route('users') }}" class="mobile-nav-link {{ request()->routeIs('users') ? 'active' : '' }}">Творцы</a>
                    <a href="{{ route('about') }}" class="mobile-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">О нас</a>
                    @if (Auth::check())
                        <a href="{{ route('profile') }}" class="mobile-nav-link">
                            <img src="{{ asset('images/profile.svg') }}" alt="Профиль" class="mobile-profile-img">
                            Профиль
                        </a>
                    @else
                        <a href="{{ route('auth') }}" class="mobile-nav-link mobile-auth-btn">
                            Войти
                        </a>
                    @endif
                </div>
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
                <a href="{{ route('home') }}">Главная</a>
                <a href="{{ route('posts') }}">Произведения</a>
                <a href="{{ route('about') }}">О нас</a>
                <a href="{{ route('users') }}">Творцы</a>
            </div>
            <div class="mid_line">

            </div>
            <div class="politic">
                <a href="{{ route('privacy-policy') }}">Политика конфиденциальности</a>
                <a href="{{ route('terms') }}">Правила и условия</a>
            </div>
            <div class="social">
                <a class="tg" href="https://t.me/y_a_m_i_t_o" target="_blank">
                    <img src="{{ asset('images/tg.svg') }}" alt="Логотип компании">
                </a>
                <a class="vk" href="https://vk.com/yamito_404" target="_blank">
                    <img src="{{ asset('images/vk.svg') }}" alt="Логотип компании">
                </a>
                <a class="mail" href="mailto:aaasss123ran@mail.ru" target="_blank">
                    <img src="{{ asset('images/mail.svg') }}" alt="Логотип компании">
                </a>
            </div>
            <p class="©">© 2024 WriteNow. Все права защищены</p>

        </footer>
    </div>

    <div id="toast-container" style="position: fixed; top: 30px; right: 30px; z-index: 9999;"></div>
    <script src="{{ asset('js/script.js') }}?v={{ filemtime(public_path('js/script.js')) }}"></script>
    <script>
        window.LaravelToast = {
            success: @json(session('success')),
            error: @json(session('error'))
        };
    </script>

    <script>
        // Бургер меню
        document.addEventListener('DOMContentLoaded', function() {
            const burgerBtn = document.getElementById('burgerBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileCloseBtn = document.getElementById('mobileCloseBtn');
            const body = document.body;

            // Функция для закрытия меню
            function closeMenu() {
                burgerBtn.classList.remove('active');
                mobileMenu.classList.remove('active');
                body.classList.remove('menu-open');
            }

            // Функция для открытия меню
            function openMenu() {
                burgerBtn.classList.add('active');
                mobileMenu.classList.add('active');
                body.classList.add('menu-open');
            }

            // Открытие меню по клику на бургер
            burgerBtn.addEventListener('click', function() {
                if (mobileMenu.classList.contains('active')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });

            // Закрытие меню по клику на крестик
            mobileCloseBtn.addEventListener('click', function() {
                closeMenu();
            });

            // Закрытие меню при клике на ссылку
            const mobileLinks = document.querySelectorAll('.mobile-nav-link');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    closeMenu();
                });
            });

            // Закрытие меню при клике вне его
            document.addEventListener('click', function(event) {
                if (!burgerBtn.contains(event.target) && 
                    !mobileMenu.contains(event.target) && 
                    !mobileCloseBtn.contains(event.target)) {
                    closeMenu();
                }
            });

            // Закрытие меню по нажатию Escape
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && mobileMenu.classList.contains('active')) {
                    closeMenu();
                }
            });
        });
    </script>
</body>

</html>