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
            <a href="{{ route('profile') }}" class="back">
                🠔 Вернуться
            </a>
        </section>
        <section class="content">
            <h1 class="title">Мои подписки</h1>
            <table class="following-table">
                <thead>
                    <tr>
                        <th>Аватар</th>
                        <th>Имя</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($following as $followed)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/avatars/' . ($followed->avatar ?? 'default-avatar.svg')) }}" 
                                 alt="{{ $followed->name }}" 
                                 class="default-avatar">
                        </td>
                        <td>
                            <a href="{{ route('showUser', $followed) }}" class="text-decoration-none">
                                {{ $followed->name }}
                            </a>
                        </td>
                        <td>
                            @if(auth()->id() === $user->id)
                                <form action="{{ route('users.unfollow', $followed) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn_content">
                                        Отписаться
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center;">У пользователя пока нет подписок</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $following->links() }}
            </div>
        </section>
    </div>
</body>

</html>