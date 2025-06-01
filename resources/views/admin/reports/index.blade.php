@extends('layouts.app')

@section('content')
<div class="container" style="z-index: 10;">
    <h1 class="mb-4">Жалобы</h1>
    <table class="table table-bordered">
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
                    <td>{{ $report->user->name ?? '—' }}</td>
                    <td>
                        @if($report->reportable)
                            @if($report->reportable_type === 'App\\Models\\Post')
                                Пост: <a href="{{ route('posts.show', $report->reportable_id) }}">#{{ $report->reportable_id }}</a>
                            @else
                                {{ class_basename($report->reportable_type) }}: #{{ $report->reportable_id }}
                            @endif
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $report->reason }}</td>
                    <td>{{ $report->description }}</td>
                    <td>{{ $report->status }}</td>
                    <td>
                        @if($report->status === 'pending')
                            <form action="{{ route('reports.resolve', $report) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Принять</button>
                            </form>
                            <form action="{{ route('reports.reject', $report) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Отклонить</button>
                            </form>
                        @else
                            <span>—</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $reports->links() }}
    </div>
</div>
@endsection 