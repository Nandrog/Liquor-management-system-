<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Add this back for forms --}}
    <title>{{ config('app.name', 'Liquor Management System') }} - @yield('title', 'Dashboard')</title> {{-- Use @yield('title') here --}}

    <!-- Fonts (Optional, as discussed previously) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts and CSS -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app" class="d-flex"> {{-- Use d-flex for sidebar layout --}}

        {{-- Optional: Your sidebar partial (if uncommented) --}}
        {{-- @include('layouts.partials.sidebar') --}}

        <div class="main-content flex-grow-1"> {{-- This div usually wraps main content and header --}}
            {{-- Optional: Your header/top-nav partial (if uncommented) --}}
            {{-- @include('layouts.partials.header') --}}

            {{-- THIS IS WHERE YOUR <MAIN> TAG SHOULD GO --}}
            <main class="container-fluid py-4"> {{-- Example Bootstrap classes --}}
                @yield('content') {{-- Page-specific content injection point --}}
            </main>
        </div>
    </div>
</body>
</html>
