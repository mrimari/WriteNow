@extends('layouts.app')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Жанры произведений</h1>
        <p class="admin-subtitle">Добавление, редактирование и удаление жанров</p>
    </div>
    @include('admin.navigation')
    <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-success" style="margin-bottom: 20px;">Добавить жанр</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="admin-btn admin-btn-primary">Редактировать</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Удалить жанр?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn admin-btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 