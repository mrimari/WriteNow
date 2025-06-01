@extends('layouts.app')

@section('content')
<div class="container" style="z-index: 10;">
    <h1 class="mb-4">Посты</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Заголовок</th>
                <th>Автор</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->user->name ?? '—' }}</td>
                    <td>{{ $post->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.posts.delete', $post) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Удалить пост?');">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="reportable_type" value="App\Models\Post">
                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $posts->links() }}
    </div>
</div>
@endsection 