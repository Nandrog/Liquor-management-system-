<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- ... meta tags, etc. ... --}}
    <title>{{ config('app.name', 'LMS') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        @stack('styles')
</head>

@php
    $user = auth()->user();
    $themeClass = $user ? 'theme-' . ($user->getRoleNames()->first() ?? 'default') : 'theme-default';
@endphp

<body class="font-sans antialiased {{ $themeClass }}">
    <div class="app-container d-flex">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
