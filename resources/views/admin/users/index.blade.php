@extends('layouts.app')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Управление пользователями</h1>
        <p class="admin-subtitle">Просмотр и управление аккаунтами пользователей</p>
    </div>
    
    @include('admin.navigation')
    
    <div class="admin-table-container" id="admin-users-table">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Админ</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->is_admin)
                                <span class="admin-badge">Админ</span>
                            @else
                                <span>—</span>
                            @endif
                        </td>
                        <td>
                            @if($user->banned)
                                <span class="admin-status admin-status-rejected">Забанен</span>
                            @else
                                <span class="admin-status admin-status-resolved">Активен</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="admin-btn admin-btn-warning">
                                    {{ $user->is_admin ? 'Снять админа' : 'Сделать админом' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Удалить пользователя?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn admin-btn-danger">Удалить</button>
                            </form>
                            @if(!$user->banned)
                                <form action="{{ route('admin.users.ban', $user) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="admin-btn admin-btn-secondary">Забанить</button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.unban', $user) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="admin-btn admin-btn-success">Разбанить</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="admin-pagination">
            {!! preg_replace('/<a /', '<a class="admin-pagination-link" ', $users->links()) !!}
        </div>
    </div>
</div>
@endsection 