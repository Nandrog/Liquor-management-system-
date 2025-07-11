<aside class="sidebar theme-{{ strtolower(auth()->user()->getRoleNames()->first() ?? 'default') }}">
    <div class="sidebar-header">
        {{-- Display the role name dynamically --}}
        <h3 class="role-title">{{ Str::title(auth()->user()->getRoleNames()->first() ?? 'User') }}</h3>
    </div>

    <nav class="sidebar-nav">
        <ul class="list-unstyled">
            {{-- Common Links --}}
            <li>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill me-2"></i> Home
                </a>
            </li>

            {{-- Supplier Links --}}
            @if(auth()->user()->hasRole('Supplier'))
                <li><a href="#" class="nav-link"><i class="bi bi-wallet2 me-2"></i> Payments</a></li>
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="{{route('supplier.orders.create')}}" class="nav-link"><i class="bi bi-truck me-2"></i> Orders</a></li>
                <li><a href="{{route('supplier.orders.index')}}" class="nav-link"><i class="bi bi-truck me-2"></i> Orders view</a></li>
                <li><a href="#" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a></li>
                <li><a href="#" class="nav-link"><i class="bi bi-graph-up me-2"></i> Analytics</a></li>
            @endif

            {{-- Manufacturer Links --}}
            @if(auth()->user()->hasRole('Manufacturer'))
                <li><a href="#" class="nav-link"><i class="bi bi-wallet2 me-2"></i> My Details</a></li>
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="{{route('manufacturer.manufacturer-index')}}" class="nav-link"><i class="bi bi-truck me-2"></i> Orders</a></li>
                <li><a href="#" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a></li>
            @endif

            {{-- Procurement Officer --}}
            @if(auth()->user()->hasRole('Procurement Officer'))
                {{-- Add specific Procurement Officer links here if needed --}}
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="{{ route('procurement.orders.index') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Orders</a></li>
            @endif

            {{-- Finance --}}
            @if(auth()->user()->hasRole('Finance'))
                <li><a href="#" class="nav-link"><i class="bi bi-wallet2 me-2"></i> My Details</a></li>
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="#" class="nav-link"><i class="bi bi-truck me-2"></i> Orders</a></li>
                <li><a href="#" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a></li>
            @endif

            {{-- Vendor --}}
            @if(auth()->user()->hasRole('Vendor'))
                {{-- Add specific Vendor links here if needed --}}
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
            @endif

            {{-- Customer --}}
            @if(auth()->user()->hasRole('Customer'))
                {{-- Add specific Customer links here if needed --}}
                {{-- Manager-specific links --}}
                <li><a href="{{ route('customer.orders.index') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Order</a></li
            @endif

            {{-- Liquor Manager --}}
            @if(auth()->user()->hasRole('Liquor Manager'))
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="{{ route('liquor-manager.products.index') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Products</a></li>
            @endif

            {{-- ✅ Workforce block for Finance, Procurement Officer, Liquor Manager --}}
            @if(auth()->user()->hasAnyRole(['Finance', 'Procurement Officer', 'Liquor Manager']))
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
            <li><a href="{{ route('messages.index') }} " class="nav-link"><i class="bi bi-chat-dots me-2"></i> Chat</a></li>
        </ul>
    </nav>
</aside>
