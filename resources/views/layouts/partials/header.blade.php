{{-- resources/views/layouts/partials/header.blade.php --}}

<header class="top-header d-flex justify-content-between align-items-center px-4 py-2">
    
    {{-- LOGO added here --}}
    <a href="{{ url('/') }}" class="d-flex align-items-center me-3">
        <img src="{{ asset('images/liquor logo.jpg') }}" alt="Liquor Logo" style="max-height: 50px;">
    </a>

    {{-- NEW: Slogan and Hamburger Icon for Mobile --}}
    <div class="d-flex align-items-center">
        <i class="bi bi-list me-2 d-md-none sidebar-toggle-btn"></i> {{-- Hamburger icon for mobile --}}
        <span class="header-slogan d-none d-md-block">"Your Partner in Premium Beverages"</span>
    </div>

    {{-- Right Side: Notifications + Profile + Logout --}}
    <div class="d-flex align-items-center">
        
        {{-- ✅ Notifications Bell --}}
        <div class="dropdown me-3">
            <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell fs-5"></i>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="notificationsDropdown" style="min-width: 300px;">
                @forelse(auth()->user()->unreadNotifications as $notification)
                    @php $data = $notification->data; @endphp
                    <li class="px-3 py-2 border-bottom small">
                        📌 <strong>{{ $data['type'] ?? 'Task' }}</strong><br>
                        📅 Due: {{ \Carbon\Carbon::parse($data['deadline'])->format('Y-m-d H:i') }}<br>
                        @if(!empty($data['order_id']))
                            🔗 Order: #{{ $data['order_id'] }}<br>
                        @endif
                        <a href="{{ route('notifications.read', $notification->id) }}" class="btn btn-sm btn-link p-0">Mark as Read</a>
                    </li>
                @empty
                    <li class="px-3 py-2 text-muted small">No new notifications</li>
                @endforelse

                @if(auth()->user()->unreadNotifications->count() > 0)
                    <li class="px-3 py-2 text-end">
                        <a href="{{ route('notifications.markAllRead') }}" class="btn btn-sm btn-outline-secondary">Mark All as Read</a>
                    </li>
                @endif
            </ul>
        </div>

        {{-- Profile Link --}}
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
