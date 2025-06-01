@extends('layouts.app')

@section('content')
<div class="followers-page" style="z-index: 10;">
    <h2>Подписчики {{ $user->name }}</h2>
    
    <div class="followers-list">
        @forelse($followers as $follower)
            <div class="follower-card">
                <img src="{{ asset('storage/avatars/' . ($follower->avatar ?? 'default-avatar.svg')) }}" alt="{{ $follower->name }}" class="follower-avatar">
                <div class="follower-info">
                    <a href="{{ route('showUser', $follower) }}" class="follower-name">{{ $follower->name }}</a>
                    <p class="follower-about">{{ Str::limit($follower->about, 100) }}</p>
                </div>
                @if(Auth::check() && Auth::id() !== $follower->id)
                    @if(Auth::user()->isFollowing($follower))
                        <form action="{{ route('users.unfollow', $follower) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="unfollow-btn">Отписаться</button>
                        </form>
                    @else
                        <form action="{{ route('users.follow', $follower) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="follow-btn">Подписаться</button>
                        </form>
                    @endif
                @endif
            </div>
        @empty
            <p class="no-followers">У этого пользователя пока нет подписчиков</p>
        @endforelse
    </div>

    <div class="pagination">
        {{ $followers->links() }}
    </div>
</div>

<style>
.followers-page {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.followers-page h2 {
    margin-bottom: 20px;
    color: #333;
}

.followers-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.follower-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.follower-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.follower-info {
    flex-grow: 1;
}

.follower-name {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    text-decoration: none;
}

.follower-name:hover {
    color: #666;
}

.follower-about {
    color: #666;
    margin-top: 5px;
    font-size: 14px;
}

.no-followers {
    text-align: center;
    color: #666;
    padding: 20px;
}

.pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}
</style>
@endsection 