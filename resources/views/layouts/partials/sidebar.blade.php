<aside class="sidebar theme-{{ strtolower(auth()->user()->getRoleNames()->first() ?? 'default') }}">
    <div class="sidebar-header">
        {{-- Display the role name dynamically --}}
        <h3 class="role-title">{{ Str::title(auth()->user()->getRoleNames()->first() ?? 'User') }}</h3>
    </div>
    <nav class="sidebar-nav">
        <ul class="list-unstyled">
            {{-- Common Links --}}
            <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-house-door-fill me-2"></i> Home</a></li>

            {{-- Supplier Links --}}
            @if(auth()->user()->hasRole('Supplier'))
                <li><a href="#" class="nav-link"><i class="bi bi-wallet2 me-2"></i> Payments</a></li>
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="{{route('supplier.orders.index')}}" class="nav-link"><i class="bi bi-truck me-2"></i> Orders</a></li>
                <li><a href="#" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a></li>
                <li><a href="#" class="nav-link"><i class="bi bi-graph-up me-2"></i> Analytics</a></li>
            @endif

            {{-- Add other role blocks here --}}
            @if(auth()->user()->hasRole('Manufacturer'))
                <li><a href="#" class="nav-link"><i class="bi bi-wallet2 me-2"></i> My Details</a></li>
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="{{route('manufacturer.manufacturer-index')}}" class="nav-link"><i class="bi bi-truck me-2"></i> Orders</a></li>
                <li><a href="#" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a></li>
            @endif

            @if(auth()->user()->hasRole('Procurement Officer'))
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="{{ route('procurement.orders.index') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Orders</a></li>
            @endif

            @if(auth()->user()->hasRole('Finance'))
                <li><a href="#" class="nav-link"><i class="bi bi-wallet2 me-2"></i> My Details</a></li>
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="#" class="nav-link"><i class="bi bi-truck me-2"></i> Orders</a></li>
                <li><a href="#" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a></li>
            @endif

            @if(auth()->user()->hasRole('Vendor'))
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
            @endif

            @if(auth()->user()->hasRole('Customer'))
                {{-- Manager-specific links --}}
                <li><a href="{{ route('customer.orders.index') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Order</a></li
            @endif

            @if(auth()->user()->hasRole('Liquor Manager'))
                <li><a href="{{ route('inventory.dashboard') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventory</a></li>
                <li><a href="{{ route('liquor-manager.products.create') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Products</a></li>
            @endif


            {{-- Common Links at the bottom --}}

            <li><a href="{{ route('messages.index') }} " class="nav-link"><i class="bi bi-chat-dots me-2"></i> Chat</a></li>

        </ul>
    </nav>
</aside>
