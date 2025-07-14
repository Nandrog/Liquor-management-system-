<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- ... meta tags, etc. ... --}}
    <title>{{ config('app.name', 'LMS') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
</head>

@php
    $user = auth()->user();
    $themeClass = $user ? 'theme-' . ($user->getRoleNames()->first() ?? 'default') : 'theme-default';
@endphp

<body class="font-sans antialiased {{ $themeClass }}">
    <div id="app" class="app-container d-flex">
        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        <div class="main-content flex-grow-1">
            {{-- Header --}}
            @include('layouts.partials.header')

            <!-- Page Content -->
            <main class="container-fluid py-4">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
