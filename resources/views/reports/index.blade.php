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
                    <h5 class="mb-0">Жалобы</h5>
                </div>

                <div class="card-body">
                    @forelse($reports as $report)
                        <div class="report-item mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        Жалоба от {{ $report->user->name }}
                                        <span class="badge bg-{{ $report->status === 'pending' ? 'warning' : ($report->status === 'resolved' ? 'success' : 'danger') }}">
                                            {{ $report->status }}
                                        </span>
                                    </h6>
                                    <p class="mb-1">
                                        <strong>Причина:</strong> {{ $report->reason }}
                                    </p>
                                    @if($report->description)
                                        <p class="mb-1">
                                            <strong>Описание:</strong> {{ $report->description }}
                                        </p>
                                    @endif
                                    <p class="mb-1">
                                        <strong>Тип:</strong> {{ class_basename($report->reportable_type) }}
                                    </p>
                                    <small class="text-muted">
                                        {{ $report->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div>
                                    <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-outline-primary">
                                        Подробнее
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center mb-0">Жалоб пока нет</p>
                    @endforelse

                    <div class="d-flex justify-content-center">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 