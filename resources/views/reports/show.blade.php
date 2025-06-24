@extends('layouts.app')

@section('style')
<style>
    .report-card {
        max-width: 600px;
        margin: 60px auto 40px auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px 0 rgba(151,90,28,0.10);
        border: 2px solid #e6ceb2;
        padding: 36px 32px 28px 32px;
        position: relative;
    }
    .report-card h1 {
        font-size: 2em;
        color: #301b00;
        font-weight: bold;
        margin-bottom: 8px;
        text-align: left;
    }
    .report-card .admin-subtitle {
        text-align: left;
        margin-bottom: 28px;
    }
    .report-info-row {
        display: flex;
        margin-bottom: 14px;
        align-items: flex-start;
    }
    .report-info-label {
        min-width: 140px;
        color: #975a1c;
        font-weight: 600;
        font-size: 1em;
        margin-right: 10px;
        text-align: left;
    }
    .report-info-value {
        color: #301b00;
        font-size: 1em;
        text-align: left;
        word-break: break-word;
    }
    .report-status {
        font-weight: bold;
        padding: 4px 14px;
        border-radius: 8px;
        font-size: 1em;
        display: inline-block;
        margin-bottom: 8px;
    }
    .report-status-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .report-status-resolved { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .report-status-rejected { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .report-content-block {
        background: #f8f6f0;
        border: 2px solid #e6ceb2;
        border-radius: 12px;
        padding: 20px 18px;
        margin: 28px 0 18px 0;
        box-shadow: 0 2px 12px 0 rgba(151,90,28,0.06);
    }
    .report-content-title {
        color: #975a1c;
        font-size: 1.1em;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .report-content-text {
        color: #301b00;
        font-size: 1em;
        margin-bottom: 10px;
        line-height: 1.5;
    }
    .report-content-author {
        color: #975a1c;
        font-size: 0.95em;
        margin-bottom: 10px;
    }
    .admin-empty-state {
        background: #fff8f0;
        border: 2px dashed #e6ceb2;
        border-radius: 12px;
        padding: 30px 20px;
        text-align: center;
        color: #975a1c;
        font-size: 1.1em;
        margin: 0 auto;
    }
    .report-actions {
        display: flex;
        gap: 18px;
        justify-content: flex-end;
        margin-top: 30px;
    }
    .report-back {
        display: flex;
        justify-content: center;
        margin-top: 38px;
    }
</style>
@endsection

@section('content')
<div class="report-card">
    <h1>Жалоба #{{ $report->id }}</h1>
    <div class="admin-subtitle">Детальная информация</div>
    <div class="report-info-row">
        <div class="report-info-label">Статус</div>
        <div class="report-info-value">
            @if($report->status === 'pending')
                <span class="report-status report-status-pending">Ожидает рассмотрения</span>
            @elseif($report->status === 'resolved')
                <span class="report-status report-status-resolved">Принята</span>
            @elseif($report->status === 'rejected')
                <span class="report-status report-status-rejected">Отклонена</span>
            @else
                <span class="report-status">{{ $report->status }}</span>
            @endif
        </div>
    </div>
    <div class="report-info-row">
        <div class="report-info-label">Отправитель</div>
        <div class="report-info-value">{{ $report->user->name ?? 'Неизвестный пользователь' }}</div>
    </div>
    <div class="report-info-row">
        <div class="report-info-label">Причина</div>
        <div class="report-info-value">{{ $report->reason }}</div>
    </div>
    @if($report->description)
        <div class="report-info-row">
            <div class="report-info-label">Описание</div>
            <div class="report-info-value">{{ $report->description }}</div>
        </div>
    @endif
    <div class="report-info-row">
        <div class="report-info-label">Тип контента</div>
        <div class="report-info-value">
            @if($report->reportable_type === 'App\\Models\\Post')
                Пост
            @elseif($report->reportable_type === 'App\\Models\\Comment')
                Комментарий
            @else
                {{ class_basename($report->reportable_type) }}
            @endif
        </div>
    </div>
    <div class="report-info-row">
        <div class="report-info-label">Дата создания</div>
        <div class="report-info-value">{{ $report->created_at->format('d.m.Y H:i') }}</div>
    </div>

    @if($report->reportable)
        <div class="report-content-block">
            <div class="report-content-title">Контент</div>
            @if($report->reportable_type === 'App\\Models\\Post')
                <div class="report-content-title" style="font-size: 1em; margin-bottom: 8px;">{{ $report->reportable->title }}</div>
                <div class="report-content-text">{{ Str::limit($report->reportable->content, 600) }}@if(strlen($report->reportable->content) > 600)<span style="color: #975a1c;">...</span>@endif</div>
                <div class="report-content-author">Автор: {{ $report->reportable->user->name ?? 'Неизвестно' }} | Создан: {{ $report->reportable->created_at->format('d.m.Y') }}</div>
                <a href="{{ route('posts.show', $report->reportable) }}" class="admin-btn admin-btn-primary" target="_blank">Открыть пост</a>
            @elseif($report->reportable_type === 'App\\Models\\Comment')
                <div class="report-content-text">{{ $report->reportable->content }}</div>
                <div class="report-content-author">Автор: {{ $report->reportable->user->name ?? 'Неизвестно' }} | Создан: {{ $report->reportable->created_at->format('d.m.Y') }}</div>
            @else
                <div class="admin-empty-state">Контент недоступен для предварительного просмотра</div>
            @endif
        </div>
    @else
        <div class="admin-empty-state">Контент был удален</div>
    @endif

    @if($report->status === 'pending')
        <div class="report-actions">
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
    @endif
    <div class="report-back">
        <a href="{{ route('admin.reports') }}" class="admin-btn admin-btn-primary">
            Назад к списку
        </a>
    </div>
</div>
@endsection 