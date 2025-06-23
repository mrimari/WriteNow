@extends('layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/posts.css') }}?v={{ time() }}">
@endsection
@section('content')
    <form method="GET" action="{{ route('posts') }}" class="search_form" data-filter-form>
        @csrf
        <input value="{{ request('search') }}" class="search_input" type="text" name="search_title"
            placeholder="Введите название" value="">
            <button class="search_button">Поиск</button>
        <select name="genre" id="" class="genre">
            <option value="">Все жанры</option>
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
        <input class="id_input" type="text" placeholder="id произведения" value="{{ request('search_id') }}"
            name="search_id">
       
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
                    e.target.classList.contains('genre') ||
                    e.target.classList.contains('forma') ||
                    e.target.classList.contains('id_input') ||
                    e.target.classList.contains('all_works') ||
                    e.target.classList.contains('new_works') ||
                    e.target.classList.contains('old_works')) {
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