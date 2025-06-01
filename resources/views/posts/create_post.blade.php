<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/create_post.css') }}">
    <style>
        .loading {
            display: none;
            margin: 10px 0;
            text-align: center;
            color: #975a1c;
        }

        .error-stats {
            display: none;
            margin: 10px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            color: #301b00;
        }

        mark {
            background-color: #ffd7d7;
            padding: 2px;
            border-radius: 3px;
            cursor: pointer;
        }

        mark:hover {
            background-color: #ffb7b7;
        }

        .error-tooltip {
            position: absolute;
            background: #301b00;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            z-index: 1000;
            display: none;
        }

        .suggestions {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }

        .suggestion {
            cursor: pointer;
            padding: 2px 5px;
            margin: 2px;
            background: #e9ecef;
            border-radius: 3px;
            display: inline-block;
        }

        .suggestion:hover {
            background: #dee2e6;
        }
    </style>
    <title>Document</title>
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
        <form method="POST" action="{{ route('create_post') }}" class="content">
            @csrf
            <input type="text" class="title_input" name="title" placeholder="Введите название" value="" required>
            @error('title')
                {{ $message }}
            @enderror
            <div id="editor" class="content_content" contenteditable="true" spellcheck="false"
                placeholder="Введите текст..."></div>
            @error('content')
                {{ $message }}
            @enderror
            <input type="hidden" name="content" id="content-hidden">
            <select name="genre" id="" class="genre" required>
                <option value="">Жанр</option>
                <option value="Романтика">Романтика</option>
                <option value="Фантастика">Фантастика</option>
                <option value="Психология">Психология</option>
                <option value="Детектив">Детектив</option>
            </select>
            @error('genre')
                {{ $message }}
            @enderror
            <select name="form" id="form-select" class="forma" required>
                <option value="">Форма</option>
                <option value="стихотворение">Стихотворение</option>
                <option value="проза">Проза</option>
            </select>
            @error('format')
                {{ $message }}
            @enderror
            <button type="submit" name="action" value="publish" class="btn_content">Опубликовать</button>
            <button type="submit" name="action" value="draft" class="btn_content">В черновик</button>
            <button type="button" class="btn_content" onclick="checkText(event)">Проверить ошибки</button>
            <div id="loading" class="loading">Проверка текста...</div>
            <div id="error-stats" class="error-stats"></div>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </form>
    </div>
    <div id="error-tooltip" class="error-tooltip"></div>
</body>

<script>
    async function checkText(event) {
        event.preventDefault();

        const editor = document.getElementById('editor');
        const loading = document.getElementById('loading');
        const errorStats = document.getElementById('error-stats');
        const rawText = editor.innerText;

        if (!rawText.trim()) {
            alert('Пожалуйста, введите текст для проверки');
            return;
        }

        // Очищаем редактор от старых <mark> — оставляем только текст
        editor.innerText = rawText;

        // Сохраняем оригинальный контент
        const originalContent = editor.innerHTML;

        // Показываем индикатор загрузки
        loading.style.display = 'block';
        errorStats.style.display = 'none';

        try {
            const response = await fetch('/api/check-text', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ text: rawText })
            });

            const data = await response.json();

            if (data.error) {
                throw new Error(data.message || 'Ошибка при проверке текста');
            }

            if (!data.matches || data.matches.length === 0) {
                errorStats.innerHTML = 'Ошибок не найдено';
                errorStats.style.display = 'block';
                document.getElementById('content-hidden').value = rawText;
                return;
            }

            let markedText = rawText;
            const matches = data.matches;

            // Сортируем ошибки с конца текста, чтобы не нарушить индексы
            matches.sort((a, b) => b.offset - a.offset).forEach(match => {
                const { offset, length, message, replacements } = match;
                const original = markedText.substring(offset, offset + length);
                // Только <mark>, без suggestions в тексте
                const replacement = `<mark data-message="${message.replace(/"/g, '&quot;')}" data-original="${original}" data-replacements='${JSON.stringify(replacements || [])}'>${original}</mark>`;
                markedText =
                    markedText.slice(0, offset) +
                    replacement +
                    markedText.slice(offset + length);
            });

            editor.innerHTML = markedText;

            // Показываем статистику
            errorStats.innerHTML = `Найдено ошибок: ${matches.length}`;
            errorStats.style.display = 'block';

            // Добавляем обработчики для подсказок
            document.querySelectorAll('mark').forEach(mark => {
                mark.addEventListener('mouseover', showTooltip);
                mark.addEventListener('mouseout', hideTooltip);
                mark.addEventListener('mousemove', moveTooltip);
            });

            // Обновляем скрытое поле для отправки формы
            document.getElementById('content-hidden').value = rawText;
        } catch (error) {
            alert(error.message);
        } finally {
            loading.style.display = 'none';
        }
    }

    function showTooltip(event) {
        const tooltip = document.getElementById('error-tooltip');
        const message = event.target.getAttribute('data-message');
        const replacements = JSON.parse(event.target.getAttribute('data-replacements'));
        let html = `<div>${message}</div>`;
        if (replacements && replacements.length > 0) {
            html += '<div style="margin-top:5px;">';
            replacements.forEach(r => {
                html += `<span class="suggestion" onclick="replaceTextFromTooltip(this, '${event.target.getAttribute('data-original')}', '${r.value}')">${r.value}</span>`;
            });
            html += '</div>';
        }
        tooltip.innerHTML = html;
        tooltip.style.display = 'block';
        tooltip.style.left = event.pageX + 10 + 'px';
        tooltip.style.top = event.pageY + 10 + 'px';
    }

    function moveTooltip(event) {
        const tooltip = document.getElementById('error-tooltip');
        tooltip.style.left = event.pageX + 10 + 'px';
        tooltip.style.top = event.pageY + 10 + 'px';
    }

    function hideTooltip() {
        const tooltip = document.getElementById('error-tooltip');
        tooltip.style.display = 'none';
    }

    function replaceTextFromTooltip(el, original, replacement) {
        const editor = document.getElementById('editor');
        // Заменяем только ближайший <mark> с нужным текстом
        const marks = editor.querySelectorAll('mark');
        for (let mark of marks) {
            if (mark.textContent === original) {
                mark.outerHTML = replacement;
                break;
            }
        }
        document.getElementById('content-hidden').value = editor.innerText;
        hideTooltip();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const formSelect = document.getElementById('form-select');
        const editor = document.getElementById('editor');
        const contentHidden = document.getElementById('content-hidden');

        // Функция для обновления скрытого поля
        function updateHiddenContent() {
            contentHidden.value = editor.innerText;
        }

        // Обновляем скрытое поле при вводе
        editor.addEventListener('input', updateHiddenContent);

        function updateTextAlign() {
            const value = formSelect.value;

            // Удаляем все классы
            editor.classList.remove('text-center', 'text-justify');

            if (value === 'стихотворение') {
                editor.classList.add('text-center');
            } else if (value === 'проза') {
                editor.classList.add('text-justify');
            }
        }

        // При загрузке
        updateTextAlign();
        updateHiddenContent();

        // При изменении формы
        formSelect.addEventListener('change', updateTextAlign);
    });

</script>

</html>