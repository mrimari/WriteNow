@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ asset('css/authors.css') }}?v={{ time() }}">
@endsection
@section('content')
    <form method="GET" action="{{ route('users') }}" class="search_form" data-filter-form>
        @csrf
        <input class="search_input" type="text" name="search" placeholder="Введите название" value="{{ request('search') }}">
        <button class="search_button">Поиск</button>
        <button type="submit" name="filter" value="all" class="all_works">Все</button>
        <button type="submit" name="filter" value="new" class="new_works">Новые</button>
        <button type="submit" name="filter" value="popular" class="old_works">Популярные</button>
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

    <div class="pagination">
        {{ $users->links('vendor.pagination.custom') }}
    </div>
    

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Добавляем активные классы к кнопкам фильтров
            const currentFilter = '{{ request('filter') }}';
            const filterButtons = document.querySelectorAll('[name="filter"]');
            
            filterButtons.forEach(button => {
                if (button.value === currentFilter) {
                    button.classList.add('active');
                }
            });
            
            // Обработка разворачивания/сворачивания фильтров на мобильных устройствах
            const searchForm = document.querySelector('.search_form');
            
            // Проверяем, что мы на мобильном устройстве
            function isMobile() {
                return window.innerWidth <= 700;
            }
            
            // Обработчик клика по кнопке "Фильтры" (псевдоэлемент ::after)
            searchForm.addEventListener('click', function(e) {
                if (!isMobile()) return;
                
                // Проверяем, что клик НЕ был по элементам ввода или кнопкам
                if (e.target.tagName === 'INPUT' || 
                    e.target.tagName === 'SELECT' || 
                    e.target.tagName === 'BUTTON' ||
                    e.target.classList.contains('search_input') ||
                    e.target.classList.contains('search_button') ||
                    e.target.classList.contains('all_works') ||
                    e.target.classList.contains('new_works') ||
                    e.target.classList.contains('popular_works')) {
                    return;
                }
                
                // Проверяем, что клик был в нижней части формы (где находится кнопка "Фильтры")
                const rect = searchForm.getBoundingClientRect();
                const clickY = e.clientY - rect.top;
                const formHeight = rect.height;
                
                // Кнопка "Фильтры" находится в нижней части формы (последние 50px)
                if (clickY > formHeight - 60 && clickY < formHeight - 10) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Переключаем состояние
                    searchForm.classList.toggle('expanded');
                    
                    // Плавная прокрутка к форме, если она развернута
                    if (searchForm.classList.contains('expanded')) {
                        setTimeout(() => {
                            searchForm.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'start' 
                            });
                        }, 100);
                    }
                }
            });
            
            // Скрываем фильтры при изменении размера окна на десктоп
            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    searchForm.classList.remove('expanded');
                }
            });
        });
    </script>
@endsection