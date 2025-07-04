{{-- resources/views/layouts/partials/header.blade.php --}}

<header class="top-header d-flex justify-content-between align-items-center px-4 py-2">
    
    {{-- NEW: Slogan and Hamburger Icon for Mobile --}}
    <div class="d-flex align-items-center">
        <i class="bi bi-list me-2 d-md-none sidebar-toggle-btn"></i> {{-- Hamburger icon for mobile --}}
        <span class="header-slogan d-none d-md-block">"Your Partner in Premium Beverages"</span>
    </div>

    {{-- User Profile and Logout Section --}}
    <div class="d-flex align-items-center">
        {{-- Profile Link Button --}}
        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary me-3" title="Edit Profile">
            <i class="bi bi-person-circle me-1"></i>
            <span>{{ Auth::user()->username }}</span>
        </a>

        {{-- Logout Form --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
                    class="logout-link"
                    title="Log Out"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                <i class="bi bi-box-arrow-right fs-4"></i>
            </a>
        </form>
    </div>
</header>