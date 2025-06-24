@extends('layouts.app')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Добавить жанр</h1>
    </div>
    @include('admin.navigation')
    <form action="{{ route('admin.categories.store') }}" method="POST" class="admin-form">
        @csrf
        <div class="admin-form-group">
            <label class="admin-form-label">Название жанра</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            @error('name')<div class="alert alert-danger">{{ $message }}</div>@enderror
        </div>
        <div class="admin-form-group">
            <label class="admin-form-label">Описание (необязательно)</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            @error('description')<div class="alert alert-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="admin-btn admin-btn-success">Добавить</button>
        <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-secondary">Назад</a>
    </form>
</div>
@endsection 