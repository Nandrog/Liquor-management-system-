<div>
   <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name', 'LMS') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased theme-{{ auth()->user()->getRoleNames()->first() ?? 'default' }}">
    <div class="app-container d-flex">
        @include('layouts.partials.sidebar')

        <div class="main-content flex-grow-1">
            @include('layouts.partials.header')

            <!-- Page Content -->
            <main class="container-fluid py-4">
                {{-- This is now valid in a component --}}
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
 <!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
</div>