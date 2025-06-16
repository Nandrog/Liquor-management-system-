@extends('layouts.app') {{-- Assuming you have a base layout --}}

@section('title', 'Welcome')

@section('content')
<div class="container py-5">
    <div class="welcome-banner">
        <h1>Welcome</h1>
        <p>To Uganda Liquor Breweries Management System</p>
    </div>

    <div class="profile-selection-card">
        <h2>Please select a user profile</h2>
        <div class="row text-center justify-content-center">
            @php
                $profiles = [
                    ['name' => 'Finance', 'icon' => 'bi-currency-dollar', 'bg_class' => 'finance-bg', 'route' => 'login'], // Adjust route to specific login/dashboard
                    ['name' => 'Supplier', 'icon' => 'bi-box-seam', 'bg_class' => 'supplier-bg', 'route' => 'login'],
                    ['name' => 'Manufacturer', 'icon' => 'bi-barrel', 'bg_class' => 'manufacturer-bg', 'route' => 'login'],
                    ['name' => 'Vendor', 'icon' => 'bi-shop', 'bg_class' => 'vendor-bg', 'route' => 'login'],
                    ['name' => 'Customer', 'icon' => 'bi-cup-fill', 'bg_class' => 'customer-bg', 'route' => 'login'],
                    ['name' => 'Liquor Manager', 'icon' => 'bi-gear-fill', 'bg_class' => 'liquor-manager-bg', 'route' => 'login'],
                    ['name' => 'Procurement Officer', 'icon' => 'bi-file-earmark-bar-graph-fill', 'bg_class' => 'procurement-officer-bg', 'route' => 'login'],
                ];
            @endphp

            @foreach($profiles as $profile)
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4"> {{-- Responsive grid --}}
                    <a href="{{ route($profile['route']) }}" class="profile-icon-item">
                        <div class="icon-wrapper {{ $profile['bg_class'] }}">
                            <i class="bi {{ $profile['icon'] }}"></i>
                        </div>
                        <span class="icon-label">{{ $profile['name'] }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
