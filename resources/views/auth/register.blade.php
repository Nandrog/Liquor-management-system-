<x-guest-layout>
    @php
        $employeeRoles = ['finance', 'supplier', 'manufacturer', 'liqour manager', 'procurement officer']; 
        $currentRole = request()->get('role', 'customer');
        $isEmployee = in_array($currentRole, $employeeRoles);
        // This match expression is a clean way to map roles to theme classes.
        $themeClass = match ($currentRole) {
            'finance' => 'theme-finance',
            'supplier' => 'theme-supplier',
            'manufacturer' => 'theme-manufacturer',
            'customer' => 'theme-customer',
            'liquor manager' => 'theme-manager',
            'procurement officer' => 'theme-officer',
            'vendor' => 'theme-vendor',
            default => 'theme-default',
        };

        $imageName = match ($currentRole) {
            'supplier' => 'supplier.jpg',
            'manufacturer' => 'manufacturer.jpg',
            'finance' => 'finance.jpg',
            'customer' => 'customer.jpg',
            'liquor manager' => 'manager.jpg',
            // Add other roles...
            default => 'default.jpg',
        };
        $imageUrl = asset('images/auth/' . $imageName);
    @endphp

    <div class="d-flex auth-container">
        <div class="auth-image-panel" >
            <img src="{{ $imageUrl }}" 
                 alt="A decorative image representing the {{ $currentRole }} role." 
                 class="auth-image">
        </div>

        <div class="auth-form-panel">
            {{-- Dynamically choose the card style based on the role --}}
            <div class="auth-card {{ $themeClass}}">

                <h2 class="auth-title ">Create Account</h2>

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

                    {{-- NEW: Factory Selection Dropdown (Conditional) --}}
@if ($currentRole === 'manufacturer')
    <div class="mt-4">
        <x-input-label for="factory_id" value="Select Your Factory" />
        <select id="factory_id" name="factory_id" class="block mt-1 w-full form-select auth-input" required>
            <option value="">Please select a factory...</option>
            @foreach ($factories as $factory)
                <option value="{{ $factory->id }}" @selected(old('factory_id') == $factory->id)>
                    {{ $factory->name }} ({{ $factory->location }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('factory_id')" class="mt-2" />
    </div>
@endif

@if ($currentRole === 'supplier')
<!-- -->

<!-- ...existing form fields... -->

<!-- Add these fields for supplier registration -->
<div class="mt-4">
    <label for="location">Location</label>
    <input class="auth-input" id="location" type="text" name="location" required class="block mt-1 w-full" value="{{ old('location') }}">
</div>

<div class="mt-4">
    <label for="item_supplied">Item Supplied</label>
    <input class="auth-input" id="item_supplied" type="text" name="item_supplied" required class="block mt-1 w-full" value="{{ old('item_supplied') }}">
</div>

<div class="mt-4">
    <label for="phone">Phone</label>
    <input class="auth-input" id="phone" type="text" name="phone" required class="block mt-1 w-full" value="{{ old('phone') }}">
</div>
<!-- ...existing form fields... -->
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
