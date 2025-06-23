@extends('layouts.app')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Управление постами</h1>
        <p class="admin-subtitle">Просмотр и управление публикациями</p>
    </div>
    
    @include('admin.navigation')
    
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Заголовок</th>
                    <th>Автор</th>
                    <th>Дата создания</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>
                            <a href="{{ route('posts.show', $post) }}" class="admin-link" target="_blank">
                                {{ Str::limit($post->title, 50) }}
                            </a>
                        </td>
                        <td>{{ $post->user->name ?? '—' }}</td>
                        <td>{{ $post->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            @if($post->status === 'published')
                                <span class="admin-status admin-status-resolved">Опубликован</span>
                            @elseif($post->status === 'draft')
                                <span class="admin-status admin-status-pending">Черновик</span>
                            @else
                                <span class="admin-status admin-status-rejected">{{ $post->status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('posts.show', $post) }}" class="admin-btn admin-btn-primary" target="_blank">
                                Просмотр
                            </a>
                            <form action="{{ route('admin.posts.delete', $post) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Удалить пост?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="reportable_type" value="App\Models\Post">
                                <button type="submit" class="admin-btn admin-btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="admin-pagination">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection 