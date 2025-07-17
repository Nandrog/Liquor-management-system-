@push('body-class', 'full-page-background')

<x-guest-layout>

    @php
        // Use the asset() helper to generate a foolproof, absolute URL to your image.
        $bgImageUrl = asset('images/backgrounds/bk.jpg');
    @endphp


    
    <div class="auth-card auth-card-green">
        <h2 class="auth-title">Sign in into your account</h2>
        <p class="auth-subtitle">Welcome back! log in with your credentials</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Username -->
            <div>
                <x-input-label for="username" value="Username" class="auth-label" />
                <x-text-input id="username" class="block mt-1 w-full auth-input" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" value="Password" class="auth-label" />
                <x-text-input id="password" class="block mt-1 w-full auth-input" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <button class="ms-3 auth-button auth-button-purple">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    {{ __('Login') }}
                </button>
            </div>
        </form>

        <p class="auth-footer-link">
            Dont have an account?
            <a href="{{ route('register') }}">
                {{ __('Sign UP') }}
            </a>
        </p>
    </div>
    
</x-guest-layout>