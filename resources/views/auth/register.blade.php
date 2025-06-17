<x-guest-layout>
    @php
        // Define which roles are considered 'employees'
        $employeeRoles = ['manufacturer', 'procurement officer', 'liquor manager', 'finance'];
        $currentRole = request()->get('role', 'customer'); // Default to 'customer' if no role is passed
        $isEmployee = in_array($currentRole, $employeeRoles);
    @endphp

    <div class="d-flex auth-container">
        <div class="auth-image-panel">
            {{-- This is the gray panel on the left. You can add an image here later. --}}
        </div>

        <div class="auth-form-panel">
            {{-- Dynamically choose the card style based on the role --}}
            <div class="auth-card {{ $isEmployee ? 'auth-card-green-border' : 'auth-card-yellow-border' }}">

                <h2 class="auth-title {{ $isEmployee ? 'text-green' : 'text-yellow' }}">Create Account</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    {{-- Hidden input to pass the role to the controller --}}
                    <input type="hidden" name="role" value="{{ $currentRole }}">

                    <div class="row">
                        <!-- First Name -->
                        <div class="col-md-6">
                            <x-input-label for="firstname" value="First Name" />
                            <x-text-input id="firstname" class="block mt-1 w-full auth-input" type="text" name="firstname" :value="old('firstname')" required autofocus />
                            <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-6">
                            <x-input-label for="lastname" value="Last Name" />
                            <x-text-input id="lastname" class="block mt-1 w-full auth-input" type="text" name="lastname" :value="old('lastname')" required />
                            <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="mt-4">
                        <x-input-label for="username" value="Username" />
                        <x-text-input id="username" class="block mt-1 w-full auth-input" type="text" name="username" :value="old('username')" required />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" class="block mt-1 w-full auth-input" type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Employee ID (Conditional) -->
                    @if ($isEmployee)
                        <div class="mt-4">
                            <x-input-label for="employee_id" value="Employee ID" />
                            <x-text-input id="employee_id" class="block mt-1 w-full auth-input" type="text" name="employee_id" :value="old('employee_id')" required />
                            <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                        </div>
                    @endif
                    
                    {{-- You can add the "Contact" field here if needed, I've omitted for brevity but it's simple to add --}}

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" value="Password" />
                        <x-text-input id="password" class="block mt-1 w-full auth-input" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" value="Password Confirm" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full auth-input" type="password" name="password_confirmation" required />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button class="ms-4 auth-button {{ $isEmployee ? 'auth-button-green' : 'auth-button-yellow' }}">
                            {{ __('Create Account') }}
                        </button>
                    </div>
                </form>
                <p class="auth-footer-link">
                    Already have an account?
                    <a href="{{ route('login') }}" class="{{ $isEmployee ? 'text-green' : 'text-yellow' }}">
                        {{ __('Sign IN') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
