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
<div class="container" style="z-index: 10; position: relative;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Детали жалобы</h5>
                        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary">
                            Назад к списку
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="report-details mb-4">
                        <h6 class="mb-3">Информация о жалобе</h6>
                        <div class="mb-2">
                            <strong>Статус:</strong>
                            <span class="badge bg-{{ $report->status === 'pending' ? 'warning' : ($report->status === 'resolved' ? 'success' : 'danger') }}">
                                {{ $report->status }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <strong>Отправитель:</strong> {{ $report->user->name }}
                        </div>
                        <div class="mb-2">
                            <strong>Причина:</strong> {{ $report->reason }}
                        </div>
                        @if($report->description)
                            <div class="mb-2">
                                <strong>Описание:</strong>
                                <p class="mb-0">{{ $report->description }}</p>
                            </div>
                        @endif
                        <div class="mb-2">
                            <strong>Тип контента:</strong> {{ class_basename($report->reportable_type) }}
                        </div>
                        <div class="mb-2">
                            <strong>Дата создания:</strong> {{ $report->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>

                    <div class="reportable-content mb-4">
                        <h6 class="mb-3">Содержание контента</h6>
                        <div class="card">
                            <div class="card-body">
                                @if($report->reportable_type === 'App\\Models\\Post')
                                    <h5>{{ $report->reportable->title }}</h5>
                                    <p>{{ $report->reportable->content }}</p>
                                @elseif($report->reportable_type === 'App\\Models\\Comment')
                                    <p>{{ $report->reportable->content }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($report->status === 'pending')
                        <div class="report-actions">
                            <form action="{{ route('reports.resolve', $report) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">
                                    Разрешить
                                </button>
                            </form>

                            <form action="{{ route('reports.reject', $report) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger">
                                    Отклонить
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 