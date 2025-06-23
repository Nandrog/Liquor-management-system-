<header class="top-header d-flex justify-content-end align-items-center px-4 py-2">
    {{-- Search Bar (can be made functional later) --}}
    <div class="search-bar me-auto">
        <i class="bi bi-list me-2 d-md-none"></i> {{-- Hamburger icon for mobile --}}
        <input type="text" class="form-control" placeholder="Hinted search text...">
        <i class="bi bi-search search-icon"></i>
    </div>

    {{-- Logout Form --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a href="{{ route('logout') }}"
                class="logout-link"
                onclick="event.preventDefault();
                            this.closest('form').submit();">
            <i class="bi bi-box-arrow-right me-2"></i>
            <span>Log out</span>
        </a>
    </form>
</header>