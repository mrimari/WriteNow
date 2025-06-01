@extends('layouts.app')

@section('content')
<div class="container" style="z-index: 10;">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Панель администратора</h1>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Пользователи</h5>
                            <p class="card-text">Всего пользователей: {{ $usersCount }}</p>
                            <a href="{{ route('admin.users') }}" class="btn btn-primary">Управление пользователями</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Посты</h5>
                            <p class="card-text">Всего постов: {{ $postsCount }}</p>
                            <a href="{{ route('admin.posts') }}" class="btn btn-primary">Управление постами</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Жалобы</h5>
                            <p class="card-text">Ожидающих рассмотрения: {{ $reportsCount }}</p>
                            <a href="{{ route('admin.reports') }}" class="btn btn-primary">Управление жалобами</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 