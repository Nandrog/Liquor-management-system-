<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name', 'LMS') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

@php
    $user = auth()->user();
    $theme = $user ? $user->getRoleNames()->first() : 'default';
@endphp

<body class="font-sans antialiased theme-{{ strtolower($theme) }}">
    <div class="app-container d-flex">
        @include('layouts.partials.sidebar')

        <div class="main-content flex-grow-1">
            @include('layouts.partials.header')

            <!-- Page Content -->
            <main class="container-fluid py-4">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
