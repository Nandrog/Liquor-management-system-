@extends('layouts.welcomelayout') {{-- Assuming you have a base layout --}}

@section('title', 'Welcome')

@section('content')

<div  class="d-flex flex-column justify-content-center align-items-center min-vh-100">
<div class="container py-5">
    <div class="welcome-banner">
        <h1>Welcome</h1>
        <p>To Uganda Liquor Breweries Management System</p>
    </div>

<div class="row text-center justify-content-center">
    @php
        $profiles = [
            // We'll handle the Vendor's special case differently
            ['name' => 'Finance', 'icon' => 'bi-currency-dollar', 'bg_class' => 'finance-bg', 'route' => 'register'],
            ['name' => 'Supplier', 'icon' => 'bi-box-seam', 'bg_class' => 'supplier-bg', 'route' => 'register'],
            ['name' => 'Manufacturer', 'icon' => 'bi-barrel', 'bg_class' => 'manufacturer-bg', 'route' => 'register'],
            ['name' => 'Customer', 'icon' => 'bi-cup-fill', 'bg_class' => 'customer-bg', 'route' => 'register'],
            ['name' => 'Liquor Manager', 'icon' => 'bi-gear-fill', 'bg_class' => 'liquor-manager-bg', 'route' => 'register'],
            ['name' => 'Procurement Officer', 'icon' => 'bi-file-earmark-bar-graph-fill', 'bg_class' => 'procurement-officer-bg', 'route' => 'register'],
            // Special route for Vendors
            ['name' => 'Vendor', 'icon' => 'bi-shop', 'bg_class' => 'vendor-bg', 'route' => 'vendor.application.create'],
        ];
    @endphp

    @foreach($profiles as $profile)
        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
            {{-- Pass the role name as a parameter, except for the vendor --}}
            @if($profile['name'] === 'Vendor')
                <a href="{{ route($profile['route']) }}" class="profile-icon-item">
            @else
                <a href="{{ route($profile['route'], ['role' => strtolower($profile['name'])]) }}" class="profile-icon-item">
            @endif
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
