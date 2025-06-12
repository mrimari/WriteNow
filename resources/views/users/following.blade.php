<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/following.css') }}">
    <title>Подписки {{ $user->name }}</title>
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
                <p>Аватар</p>
                <p>Имя</p>
                <p>Дата подписки</p>
                <p>Действия</p>
            </div>
            <hr class="line_content">
            @forelse($following as $followed)
                <div class="content_content">
                    <div>
                        <img src="{{ asset('storage/avatars/' . ($followed->avatar ?? 'default-avatar.svg')) }}" 
                             alt="{{ $followed->name }}" 
                             class="default-avatar">
                    </div>
                    <div>
                        <a href="{{ route('showUser', $followed) }}" class="text-decoration-none">
                            {{ $followed->name }}
                        </a>
                    </div>
                    <div>
                        {{ $followed->pivot->created_at ? $followed->pivot->created_at->format('d.m.Y') : 'Дата не указана' }}
                    </div>
                    <div>
                        @if(auth()->id() === $user->id)
                            <form action="{{ route('users.unfollow', $followed) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn_content">
                                    Отписаться
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="content_content">
                    <p style="grid-column: span 4; text-align: center;">У пользователя пока нет подписок</p>
                </div>
            @endforelse
            
            <div style="grid-column: 1 / span 12; margin-top: 20px; display: flex; justify-content: center;">
                {{ $following->links() }}
            </div>
        </section>
    </div>
</body>

</html>