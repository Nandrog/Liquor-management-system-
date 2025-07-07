<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- ... meta tags, etc. ... --}}
        <title>{{ config('app.name', 'LMS') }}</title>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    {{-- Here we add a dynamic class based on the user's role --}}
    <body class="font-sans antialiased theme-{{ auth()->user()->getRoleNames()->first() ?? 'default' }}">
        <div class="app-container d-flex">
            {{-- Include our new sidebar partial --}}
            @include('layouts.partials.sidebar')

            <div class="main-content flex-grow-1">
                {{-- Include a header/top-bar partial --}}
                @include('layouts.partials.header')

                <!-- Page Content -->
                <main class="container-fluid py-4">
                    {{ $slot }}
                </main>
            </div>
        </div>

    </div>
</body>

    @stack('scripts')
</html>
