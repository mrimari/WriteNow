@extends('layouts.app')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Панель администратора</h1>
        <p class="admin-subtitle">Управление контентом и пользователями</p>
    </div>
    
    @include('admin.navigation')
    
    <div class="admin-stats">
        <div class="admin-card">
            <h5 class="admin-card-title">Пользователи</h5>
            <p class="admin-card-text">Всего пользователей: {{ $usersCount }}</p>
            <a href="{{ route('admin.users') }}" class="admin-card-btn">Управление пользователями</a>
        </div>
        
        <div class="admin-card">
            <h5 class="admin-card-title">Посты</h5>
            <p class="admin-card-text">Всего постов: {{ $postsCount }}</p>
            <a href="{{ route('admin.posts') }}" class="admin-card-btn">Управление постами</a>
        </div>
        
        <div class="admin-card">
            <h5 class="admin-card-title">Жалобы</h5>
            <p class="admin-card-text">Ожидающих рассмотрения: {{ $reportsCount }}</p>
            <a href="{{ route('admin.reports') }}" class="admin-card-btn">Управление жалобами</a>
        </div>
        
        <div class="admin-card">
            <h5 class="admin-card-title">Статистика</h5>
            <p class="admin-card-text">Подробная аналитика</p>
            <a href="{{ route('admin.statistics') }}" class="admin-card-btn">Просмотр статистики</a>
        </div>
    </div>
</div>
@endsection 