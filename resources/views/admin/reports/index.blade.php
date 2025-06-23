@extends('layouts.app')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Управление жалобами</h1>
        <p class="admin-subtitle">Рассмотрение и обработка жалоб пользователей</p>
    </div>
    
    @include('admin.navigation')
    
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>На что</th>
                    <th>Причина</th>
                    <th>Описание</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span>{{ $report->user->name ?? '—' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($report->reportable)
                                @if($report->reportable_type === 'App\\Models\\Post')
                                    <a href="{{ route('posts.show', $report->reportable_id) }}" class="admin-link" target="_blank">
                                        📝 Пост #{{ $report->reportable_id }}
                                    </a>
                                @else
                                    <span class="admin-badge">{{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}</span>
                                @endif
                            @else
                                <span class="admin-status admin-status-rejected">Удален</span>
                            @endif
                        </td>
                        <td>
                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $report->reason }}
                            </div>
                        </td>
                        <td>
                            @if($report->description)
                                <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $report->description }}">
                                    {{ Str::limit($report->description, 50) }}
                                </div>
                            @else
                                <span style="color: #975a1c; font-style: italic;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($report->status === 'pending')
                                <span class="admin-status admin-status-pending">Ожидает</span>
                            @elseif($report->status === 'resolved')
                                <span class="admin-status admin-status-resolved">Принята</span>
                            @elseif($report->status === 'rejected')
                                <span class="admin-status admin-status-rejected">Отклонена</span>
                            @else
                                <span class="admin-status">{{ $report->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <a href="{{ route('reports.show', $report) }}" class="admin-btn admin-btn-primary" title="Просмотреть детали">
                                    Просмотреть
                                </a>
                                @if($report->status === 'pending')
                                    <form action="{{ route('reports.resolve', $report) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="admin-btn admin-btn-success" title="Принять жалобу" onclick="return confirm('Принять жалобу?')">
                                            Принять
                                        </button>
                                    </form>
                                    <form action="{{ route('reports.reject', $report) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="admin-btn admin-btn-danger" title="Отклонить жалобу" onclick="return confirm('Отклонить жалобу?')">
                                            Отклонить
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="admin-pagination">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection 