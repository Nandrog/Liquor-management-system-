<x-guest-layout>
    @php
        
        $currentRole = request()->get('role', 'vendor');
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
            'procurement officer' => 'officer.jpg',
            'vendor' => 'vendor.jpg',

            // Add other roles...
            default => 'default.jpg',
        };
        $imageUrl = asset('images/auth/' . $imageName);
    @endphp
    {{-- This assumes you have a theme class for vendors or a generic guest layout --}}
    <div class="d-flex auth-container">
        {{-- You can add a dynamic image here if you wish --}}
        <div class="auth-image-panel">
            <img src="{{ $imageUrl }}"
                alt="A decorative image representing the {{ $currentRole }} role."
                class="auth-image">
        </div>

        <div class="auth-form-panel">
            <div class="auth-card theme-vendor">
                <h2 class="auth-title">Complete Your Vendor Registration</h2>
                <p class="auth-subtitle">Welcome, <span class="fw-bold">{{ $application->vendor_name }}</span>! Create your account to get started.</p>

                <form method="POST" action="{{ route('vendor.registration.store') }}">
                    @csrf
                    {{-- This hidden field is crucial to link the submission back to the application --}}
                    <input type="hidden" name="application_id" value="{{ $application->id }}">

                    {{-- Display read-only info from the application --}}
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" value="{{ $application->vendor_name }}" readonly disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" value="{{ $application->contact_email }}" readonly disabled>
                    </div>

                    <hr class="my-4">

                    {{-- Collect new information for the User and Vendor profiles --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstname" class="form-label">Your First Name</label>
                            <input type="text" name="firstname" id="firstname" class="form-control @error('firstname') is-invalid @enderror" value="{{ old('firstname') }}" required autofocus>
                            <x-input-error :messages="$errors->get('firstname')" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastname" class="form-label">Your Last Name</label>
                            <input type="text" name="lastname" id="lastname" class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname') }}" required>
                            <x-input-error :messages="$errors->get('lastname')" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Create a Username</label>
                        <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                        <x-input-error :messages="$errors->get('username')" />
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact Phone Number</label>
                        <input type="text" name="contact" id="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact') }}" required>
                        <x-input-error :messages="$errors->get('contact')" />
                    </div>

                    {{-- Password Fields --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                            <x-input-error :messages="$errors->get('password')" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="auth-button">Create Account & Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>