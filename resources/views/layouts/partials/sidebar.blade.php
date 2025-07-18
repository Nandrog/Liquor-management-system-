@php
    $user = auth()->user();
    $role = $user ? strtolower($user->getRoleNames()->first() ?? 'default') : 'default';
    $roleTitle = $user ? Str::title($user->getRoleNames()->first() ?? 'User') : 'User';
@endphp

<aside class="sidebar theme-{{ $role }}">
    <div class="sidebar-header">
        <h3 class="role-title">{{ $roleTitle }}</h3>
    </div>

    <nav class="sidebar-nav">
        <ul class="list-unstyled">
            {{-- Common Links --}}
            <li>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill me-2"></i> Home
                </a>
            </li>

            {{-- Role-based Links --}}
            @if($user && $user->hasRole('Supplier'))
                <li><a href="{{route('supplier.payments.index')}}" class="nav-link"><i class="bi bi-wallet2 me-2"></i> Payments</a></li>
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="{{route('supplier.orders.create')}}" class="nav-link"><i class="bi bi-truck me-2"></i> Orders</a></li>
                <li><a href="{{route('supplier.orders.index')}}" class="nav-link"><i class="bi bi-truck me-2"></i> Orders view</a></li>
                <li><a href="{{ route('reports.index') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a></li>
                <li><a href="{{route('supplier.orders.paid')}}" class="nav-link"><i class="bi bi-truck me-2"></i> Orders paid</a></li>
                {{--<li><a href="#" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a></li>--}}
                <li><a href="{{ route('analytics.menu') }}" class="nav-link"><i class="bi bi-graph-up me-2"></i> Analytics</a></li>
            @endif

            @if($user && $user->hasRole('Manufacturer'))
                <li><a href="#" class="nav-link"><i class="bi bi-wallet2 me-2"></i> My Details</a></li>
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="{{ route('manufacturer.orders.index') }}" class="nav-link"><i class="bi bi-truck me-2"></i> Orders</a></li>
                <li><a href="{{ route('reports.index') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a></li>
            @endif

            @if($user && $user->hasRole('Procurement Officer'))
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="{{ route('procurement.orders.index') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Orders</a></li>
                <li><a href="{{ route('analytics.menu') }}" class="nav-link"><i class="bi bi-graph-up me-2"></i> Analytics</a></li>
            @endif

            @if($user && $user->hasRole('Finance'))
                <li><a href="#" class="nav-link"><i class="bi bi-wallet2 me-2"></i> My Details</a></li>
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="#" class="nav-link"><i class="bi bi-truck me-2"></i> Orders</a></li>
                <li><a href="{{ route('reports.index') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a></li>
            @endif

            @if($user && $user->hasRole('Vendor'))
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
            @endif

            {{-- Customer --}}
            @if(auth()->user()->hasRole('Customer'))
                {{-- Add specific Customer links here if needed --}}
                {{-- Manager-specific links --}}
                <li><a href="{{ route('customer.orders.index') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Order</a></li>
                <li><a href="{{ route('storefront.index') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Browse Our Products</a></li>
            @endif

            @if($user && $user->hasRole('Liquor Manager'))
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="{{ route('liquor-manager.products.index') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Products</a></li>
                 <li><a href="{{ route('reports.index') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a></li>
            @endif

            {{-- Workforce (for specific roles) --}}
            @if($user && $user->hasAnyRole(['Finance', 'Procurement Officer', 'Liquor Manager','Manufacturer']))
                <li class="workforce-parent">
                    <a href="#" class="nav-link workforce-toggle">
                        <i class="bi bi-people-fill me-2"></i> Workforce
                    </a>
                    <ul class="workforce-links list-unstyled ps-4">
                        <li>
                            <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                                <i class="bi bi-list-task me-2"></i> Tasks
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('shifts.index') }}" class="nav-link {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
                                <i class="bi bi-clock-history me-2"></i> Shifts
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            {{-- Common Links at the bottom --}}
            <li><a href="{{ route('chat.page') }} " class="nav-link"><i class="bi bi-chat-dots me-2"></i> Chat</a></li>

           {{-- Analytics Access Based on Role --}}
           @if(auth()->user()->hasRole('Finance'))
                <li>
                    <a href="{{ route('analytics.menu') }}" class="nav-link">
                        <i class="bi bi-bar-chart me-2"></i> Analytics
                    </a>
                </li>
            @elseif(auth()->user()->hasRole('Liquor Manager'))
                <li>
                    <a href="{{ route('analytics.menu') }}" class="nav-link">
                        <i class="bi bi-bar-chart me-2"></i> Analytics
                    </a>
                </li>
            @elseif(auth()->user()->hasRole('Procurement Officer'))
                <li>
                    <a href="{{ route(/*changed something*/'analytics.menu') }}" class="nav-link">
                        <i class="bi bi-bar-chart me-2"></i> Analytics
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</aside>
