<div class="admin-navigation">
    <div class="admin-nav-container">
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="admin-nav-icon">📊</span>
            Панель
        </a>
        <a href="{{ route('admin.statistics') }}" class="admin-nav-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
            <span class="admin-nav-icon">📈</span>
            Статистика
        </a>
        <a href="{{ route('admin.users') }}" class="admin-nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <span class="admin-nav-icon">👥</span>
            Пользователи
        </a>
        <a href="{{ route('admin.posts') }}" class="admin-nav-link {{ request()->routeIs('admin.posts') ? 'active' : '' }}">
            <span class="admin-nav-icon">📝</span>
            Посты
        </a>
        <a href="{{ route('admin.reports') }}" class="admin-nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
            <span class="admin-nav-icon">⚠️</span>
            Жалобы
        </a>
        <a href="{{ route('admin.categories.index') }}" class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <span class="admin-nav-icon">🏷️</span>
            Жанры
        </a>
    </div>
</div> 