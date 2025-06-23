<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/edit_post.css') }}">
    <title>Редактировать пост</title>
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
        <form method="POST" action="{{ route('posts.update', $post) }}" class="content">
            @csrf
            @method('PUT')
            <input type="text" class="title_input" name="title" value="{{ old('title', $post->title) }}" required>
            @error('title'){{ $message }}@enderror

            <div id="editor" class="content_content" contenteditable="true" spellcheck="false">{{ old('content', $post->content) }}</div>
            @error('content'){{ $message }}@enderror
            <input type="hidden" name="content" id="content-hidden" value="{{ old('content', $post->content) }}">

            <select name="genre" class="genre" required>
                <option value="">Жанр</option>
                <option value="Романтика" {{ $post->genre == 'Романтика' ? 'selected' : '' }}>Романтика</option>
                <option value="Фантастика" {{ $post->genre == 'Фантастика' ? 'selected' : '' }}>Фантастика</option>
                <option value="Психология" {{ $post->genre == 'Психология' ? 'selected' : '' }}>Психология</option>
                <option value="Детектив" {{ $post->genre == 'Детектив' ? 'selected' : '' }}>Детектив</option>
            </select>
            @error('genre'){{ $message }}@enderror

            <select name="form" id="form-select" class="forma" required>
                <option value="">Форма</option>
                <option value="стихотворение" {{ $post->form == 'стихотворение' ? 'selected' : '' }}>Стихотворение</option>
                <option value="проза" {{ $post->form == 'проза' ? 'selected' : '' }}>Проза</option>
            </select>
            @error('form'){{ $message }}@enderror

            <button type="submit" name="action" value="publish" class="btn_content">Сохранить изменения</button>
            <button type="submit" name="action" value="draft" class="btn_content">В черновик</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formSelect = document.getElementById('form-select');
            const editor = document.getElementById('editor');
            const contentHidden = document.getElementById('content-hidden');
            function updateHiddenContent() {
                contentHidden.value = editor.innerText;
            }
            editor.addEventListener('input', updateHiddenContent);
            function updateTextAlign() {
                const value = formSelect.value;
                editor.classList.remove('text-center', 'text-justify');
                if (value === 'стихотворение') editor.classList.add('text-center');
                else if (value === 'проза') editor.classList.add('text-justify');
            }
            updateTextAlign();
            updateHiddenContent();
            formSelect.addEventListener('change', updateTextAlign);
        });
    </script>
</body>
</html>