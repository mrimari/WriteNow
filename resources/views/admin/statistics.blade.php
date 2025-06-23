@extends('layouts.app')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Статистика сайта</h1>
        <p class="admin-subtitle">Подробная аналитика и метрики</p>
    </div>
    
    @include('admin.navigation')
    
    <!-- Общая статистика -->
    <div class="admin-stats">
        <div class="admin-card">
            <h5 class="admin-card-title">Всего пользователей</h5>
            <p class="admin-card-text">{{ $totalUsers }}</p>
            <small style="color: #975a1c;">+{{ $newUsers }} за 30 дней</small>
        </div>
        
        <div class="admin-card">
            <h5 class="admin-card-title">Всего постов</h5>
            <p class="admin-card-text">{{ $totalPosts }}</p>
            <small style="color: #975a1c;">+{{ $newPosts }} за 30 дней</small>
        </div>
        
        <div class="admin-card">
            <h5 class="admin-card-title">Всего жалоб</h5>
            <p class="admin-card-text">{{ $totalReports }}</p>
            <small style="color: #975a1c;">+{{ $newReports }} за 30 дней</small>
        </div>
    </div>
    
    <!-- Детальная статистика -->
    <div class="admin-stats">
        <div class="admin-card">
            <h5 class="admin-card-title">Пользователи</h5>
            <div style="text-align: left; margin-bottom: 15px;">
                <div style="margin-bottom: 8px;">
                    <span style="color: #975a1c;">Активные:</span> {{ $activeUsers }}
                </div>
                <div style="margin-bottom: 8px;">
                    <span style="color: #975a1c;">Администраторы:</span> {{ $adminUsers }}
                </div>
                <div style="margin-bottom: 8px;">
                    <span style="color: #975a1c;">Забаненные:</span> {{ $bannedUsers }}
                </div>
            </div>
        </div>
        
        <div class="admin-card">
            <h5 class="admin-card-title">Посты</h5>
            <div style="text-align: left; margin-bottom: 15px;">
                <div style="margin-bottom: 8px;">
                    <span style="color: #975a1c;">Опубликованные:</span> {{ $publishedPosts }}
                </div>
                <div style="margin-bottom: 8px;">
                    <span style="color: #975a1c;">Черновики:</span> {{ $draftPosts }}
                </div>
            </div>
        </div>
        
        <div class="admin-card">
            <h5 class="admin-card-title">Жалобы</h5>
            <div style="text-align: left; margin-bottom: 15px;">
                <div style="margin-bottom: 8px;">
                    <span style="color: #975a1c;">Ожидают:</span> {{ $pendingReports }}
                </div>
                <div style="margin-bottom: 8px;">
                    <span style="color: #975a1c;">Приняты:</span> {{ $resolvedReports }}
                </div>
                <div style="margin-bottom: 8px;">
                    <span style="color: #975a1c;">Отклонены:</span> {{ $rejectedReports }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Топ авторов -->
    <div class="admin-form">
        <h3 class="admin-card-title">Топ авторов по количеству постов</h3>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Место</th>
                        <th>Автор</th>
                        <th>Количество постов</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topAuthors as $index => $author)
                        <tr>
                            <td>
                                @if($index === 0)
                                    <span style="color: #ffd700; font-weight: bold;">🥇</span>
                                @elseif($index === 1)
                                    <span style="color: #c0c0c0; font-weight: bold;">🥈</span>
                                @elseif($index === 2)
                                    <span style="color: #cd7f32; font-weight: bold;">🥉</span>
                                @else
                                    <span style="color: #975a1c;">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td>{{ $author->name }}</td>
                            <td>{{ $author->posts_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Статистика по дням -->
    <div class="admin-form">
        <h3 class="admin-card-title">Активность за последние 7 дней</h3>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Новые пользователи</th>
                        <th>Новые посты</th>
                        <th>Новые жалобы</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyStats as $stat)
                        <tr>
                            <td>{{ $stat['date'] }}</td>
                            <td>{{ $stat['users'] }}</td>
                            <td>{{ $stat['posts'] }}</td>
                            <td>{{ $stat['reports'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-primary">
            Назад к панели
        </a>
    </div>
</div>
@endsection 