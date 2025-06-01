@extends('layouts.app')

@section('content')
<div class="container" style="z-index: 10;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Подписки {{ $user->name }}</h5>
                </div>

                <div class="card-body">
                    @forelse($following as $followed)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                @if($followed->profile && $followed->profile->avatar)
                                    <img src="{{ asset('storage/' . $followed->profile->avatar) }}" 
                                         alt="{{ $followed->name }}" 
                                         class="rounded-circle"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <span class="text-white">{{ substr($followed->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">
                                    <a href="{{ route('showUser', $followed) }}" class="text-decoration-none">
                                        {{ $followed->name }}
                                    </a>
                                </h6>
                                @if($followed->profile && $followed->profile->bio)
                                    <small class="text-muted">{{ Str::limit($followed->profile->bio, 100) }}</small>
                                @endif
                            </div>
                            @if(auth()->id() === $user->id)
                                <form action="{{ route('users.unfollow', $followed) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        Отписаться
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-center mb-0">У пользователя пока нет подписок</p>
                    @endforelse

                    <div class="d-flex justify-content-center">
                        {{ $following->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 