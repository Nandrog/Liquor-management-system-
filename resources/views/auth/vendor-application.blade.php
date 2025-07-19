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

        
    @endphp
    <div class="auth-cards {{$themeClass}}">
        <h2 class="auth-title ">Vendor Application</h2>
        <p class="auth-subtitle">Please submit your application for review.</p>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('vendor.application.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Vendor Name -->
            <div class="mt-4">
                <x-input-label for="vendor_name" value="Your Company Name" />
                <x-text-input id="vendor_name" class="block mt-1 w-full auth-input" type="text" name="vendor_name" :value="old('vendor_name')" required />
            </div>

            <!-- Contact Email -->
            <div class="mt-4">
                <x-input-label for="contact_email" value="Contact Email" />
                <x-text-input id="contact_email" class="block mt-1 w-full auth-input" type="email" name="contact_email" :value="old('contact_email')" required />
            </div>

            <!-- PDF Upload -->
            <div class="mt-4">
                <x-input-label for="application_pdf" value="Application Document (PDF)" />
                <input id="application_pdf" class="block mt-1 w-full" type="file" name="application_pdf" required accept=".pdf">
            </div>

            <div class="flex items-center justify-end mt-4">
                <button class="ms-4 auth-button auth-button-yellow">
                    {{ __('Submit Application') }}
                </button>
            </div>
        </form>

         <p class="auth-footer-link mt-4">
            Already have an approved account?
            <a href="{{ route('login') }}" class="text-yellow">
                Sign In
            </a>
        </p>
        
    </div>
</x-guest-layout>
