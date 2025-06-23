<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/followers.css') }}">
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
            <div class="followers-page" style="z-index: 10;">
                <h2 class="title">Подписчики <span class="user-name">{{ $user->name }}</span></h2>
                <table class="followers-table">
                    <thead>
                        <tr>
                            <th>Аватар</th>
                            <th>Имя</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($followers as $follower)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/avatars/' . ($follower->avatar ?? 'default-avatar.svg')) }}"
                                        alt="{{ $follower->name }}" class="follower-avatar">
                                </td>
                                <td>
                                    <a href="{{ route('showUser', $follower) }}"
                                        class="follower-name">{{ $follower->name }}</a>
                                </td>
                                <td>
                                    @if(Auth::check() && Auth::id() !== $follower->id)
                                        @if(Auth::user()->isFollowing($follower))
                                            <form action="{{ route('users.unfollow', $follower) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="unfollow-btn">Отписаться</button>
                                            </form>
                                        @else
                                            <form action="{{ route('users.follow', $follower) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="follow-btn">Подписаться</button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="no-followers">У этого пользователя пока нет подписчиков</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="pagination">
                    {{ $followers->links() }}
                </div>
            </div>
        </section>
    </div>
    <!-- <style>
    .followers-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .followers-table th, .followers-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    .followers-table th {
        background: #f7f7f7;
        font-weight: bold;
    }
    .followers-table tr:last-child td {
        border-bottom: none;
    }
    .follower-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .follower-name {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        text-decoration: none;
    }
    .follower-name:hover {
        color: #666;
    }
    .unfollow-btn, .follow-btn {
        background: #f44336;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.2s;
    }
    .follow-btn {
        background: #2196f3;
    }
    .unfollow-btn:hover {
        background: #d32f2f;
    }
    .follow-btn:hover {
        background: #1976d2;
    }
    .no-followers {
        text-align: center;
        color: #666;
        padding: 20px;
    }
    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    
    /* Мобильные стили */
    @media (max-width: 768px) {
        .followers-table {
            display: block;
            border-radius: 0;
            box-shadow: none;
        }
        .followers-table thead {
            display: none;
        }
        .followers-table tbody {
            display: block;
        }
        .followers-table tr {
            display: block;
            margin-bottom: 15px;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .followers-table td {
            display: block;
            padding: 8px 0;
            border: none;
            text-align: center;
        }
        .followers-table td:first-child {
            padding-top: 0;
        }
        .followers-table td:last-child {
            padding-bottom: 0;
        }
        .follower-avatar {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
        }
        .unfollow-btn, .follow-btn {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
    }
    
    @media (max-width: 480px) {
        .header_edit {
            flex-direction: column;
            gap: 10px;
            padding: 10px;
        }
        .back {
            align-self: flex-start;
        }
        .followers-table tr {
            margin-bottom: 10px;
            padding: 10px;
        }
        .followers-page h2 {
            font-size: 20px;
            text-align: center;
        }
    }
    </style> -->
</body>

</html>