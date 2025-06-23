@extends('layouts.app')

@section('style')
<style>
    .card {
        border-radius: 18px;
        box-shadow: 0 2px 12px 0 rgba(0,0,0,0.08);
        margin-bottom: 24px;
        border: none;
        background: #fff8f0;
    }
    .card-header, .card-body {
        background: transparent;
    }
    .achievement-icon i, .report-item i {
        color: #ffb300;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.08));
    }
    .btn-outline-primary, .btn-outline-secondary, .btn-outline-danger, .btn-success, .btn-danger {
        border-radius: 10px;
        font-weight: 500;
        min-width: 110px;
        margin: 2px 0;
    }
    .btn-outline-primary:hover, .btn-outline-secondary:hover, .btn-outline-danger:hover, .btn-success:hover, .btn-danger:hover {
        filter: brightness(0.95);
        transform: scale(1.04);
        transition: 0.2s;
    }
    .card-title {
        color: #975a1c;
        font-weight: bold;
        font-size: 1.2rem;
    }
    .card-text, .text-muted {
        color: #6c757d;
    }
    .user-avatar img, .avatar-placeholder {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #ffb300;
        background: #fff;
    }
    .user-item {
        background: #f8f9fa;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    .pagination .page-link {
        border-radius: 8px !important;
        color: #975a1c;
    }
    .pagination .page-item.active .page-link {
        background: #975a1c;
        color: #fff;
        border: none;
    }
</style>
@endsection

@section('content')
<div style="margin-top: 100px; z-index: 1000;" class="admin-container">
    <div class="admin-header">
        <h1>Жалоба #{{ $report->id }}</h1>
        <p class="admin-subtitle">Детальная информация</p>
    </div>
    
    @include('admin.navigation')
    
    <!-- Основная информация -->
    <div class="admin-form">
        <div class="admin-form-group">
            <label class="admin-form-label">Статус</label>
            <div>
                @if($report->status === 'pending')
                    <span class="admin-status admin-status-pending">Ожидает рассмотрения</span>
                @elseif($report->status === 'resolved')
                    <span class="admin-status admin-status-resolved">Принята</span>
                @elseif($report->status === 'rejected')
                    <span class="admin-status admin-status-rejected">Отклонена</span>
                @else
                    <span class="admin-status">{{ $report->status }}</span>
                @endif
            </div>
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Отправитель</label>
            <div>{{ $report->user->name ?? 'Неизвестный пользователь' }}</div>
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Причина</label>
            <div>{{ $report->reason }}</div>
        </div>
        
        @if($report->description)
            <div class="admin-form-group">
                <label class="admin-form-label">Описание</label>
                <div>{{ $report->description }}</div>
            </div>
        @endif
        
        <div class="admin-form-group">
            <label class="admin-form-label">Тип контента</label>
            <div>
                @if($report->reportable_type === 'App\\Models\\Post')
                    Пост
                @elseif($report->reportable_type === 'App\\Models\\Comment')
                    Комментарий
                @else
                    {{ class_basename($report->reportable_type) }}
                @endif
            </div>
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Дата создания</label>
            <div>{{ $report->created_at->format('d.m.Y H:i') }}</div>
        </div>
    </div>
    
    <!-- Контент -->
    @if($report->reportable)
        <div class="admin-form">
            <h3 class="admin-card-title">Контент</h3>
            <div class="admin-table-container">
                @if($report->reportable_type === 'App\\Models\\Post')
                    <div style="margin-bottom: 15px;">
                        <h4 style="color: #301b00; margin-bottom: 10px;">{{ $report->reportable->title }}</h4>
                        <div style="background: rgba(255, 255, 255, 0.8); padding: 15px; border-radius: 8px; border: 1px solid #e6ceb2; margin-bottom: 15px;">
                            {{ Str::limit($report->reportable->content, 600) }}
                            @if(strlen($report->reportable->content) > 600)
                                <span style="color: #975a1c;">...</span>
                            @endif
                        </div>
                        
                        <div style="margin-bottom: 15px; font-size: 14px; color: #975a1c;">
                            Автор: {{ $report->reportable->user->name ?? 'Неизвестно' }} | 
                            Создан: {{ $report->reportable->created_at->format('d.m.Y') }}
                        </div>
                        
                        <a href="{{ route('posts.show', $report->reportable) }}" class="admin-btn admin-btn-primary" target="_blank">
                            Открыть пост
                        </a>
                    </div>
                @elseif($report->reportable_type === 'App\\Models\\Comment')
                    <div style="background: rgba(255, 255, 255, 0.8); padding: 15px; border-radius: 8px; border: 1px solid #e6ceb2; margin-bottom: 15px;">
                        <p style="margin-bottom: 10px;">{{ $report->reportable->content }}</p>
                        <div style="font-size: 14px; color: #975a1c;">
                            Автор: {{ $report->reportable->user->name ?? 'Неизвестно' }} | 
                            Создан: {{ $report->reportable->created_at->format('d.m.Y') }}
                        </div>
                    </div>
                @else
                    <p>Контент недоступен для предварительного просмотра</p>
                @endif
            </div>
        </div>
    @else
        <div class="admin-form">
            <h3 class="admin-card-title">Контент</h3>
            <div class="admin-empty-state">
                <p>Контент был удален</p>
            </div>
        </div>
    @endif
    
    <!-- Действия -->
    @if($report->status === 'pending')
        <div class="admin-form">
            <h3 class="admin-card-title">Действия</h3>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <form action="{{ route('reports.resolve', $report) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="admin-btn admin-btn-success" onclick="return confirm('Принять жалобу?')">
                        Принять
                    </button>
                </form>
                
                <form action="{{ route('reports.reject', $report) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="admin-btn admin-btn-danger" onclick="return confirm('Отклонить жалобу?')">
                        Отклонить
                    </button>
                </form>
            </div>
        </div>
    @endif
    
    <!-- Навигация -->
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('admin.reports') }}" class="admin-btn admin-btn-primary">
            Назад к списку
        </a>
    </div>
</div>
@endsection 