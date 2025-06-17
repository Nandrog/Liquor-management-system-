<x-guest-layout>
    <div class="auth-card auth-card-yellow-border">
        <h2 class="auth-title text-yellow">Vendor Application</h2>
        <p class="auth-subtitle">Please submit your application for review.</p>

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
    </div>
</x-guest-layout>