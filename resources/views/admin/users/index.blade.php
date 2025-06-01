@extends('layouts.app')

@section('content')
<div class="container" style="z-index: 10;">
    <h1 class="mb-4">Пользователи</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Админ</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->is_admin ? 'Да' : 'Нет' }}</td>
                    <td>
                        <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning">
                                {{ $user->is_admin ? 'Снять админа' : 'Сделать админом' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Удалить пользователя?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                        </form>
                        @if(!$user->banned)
                            <form action="{{ route('admin.users.ban', $user) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary">Забанить</button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.unban', $user) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Разбанить</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $users->links() }}
    </div>
</div>
@endsection 