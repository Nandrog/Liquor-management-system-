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
        <h2 class="auth-title ">Application Submitted</h2>

        <div class="mt-4">
            <p>
                <strong>Status:</strong>
                @if ($application->status === 'pending' || $application->status === null)
                    Pending — <em>Waiting for validation...</em>
                @else
                    {{ ucfirst($application->status) }}
                @endif
            </p>

            @if ($application->visit_scheduled_for)
                <p><strong>Scheduled Visit:</strong> {{ \Carbon\Carbon::parse($application->visit_scheduled_for)->format('F j, Y g:i A') }}</p>
            @endif

            @if ($application->validation_notes)
                <div class="mt-2">
                    <strong>Validation Notes:</strong>
                    <p>{{ $application->validation_notes }}</p>
                </div>
            @endif

            <div class="mt-6">
                @if ($application->status === 'approved' || $application->status === 'passed')
                    <a href="{{ route('login') }}" class="auth-button auth-button-yellow">
                        Proceed to Login
                    </a>
                @elseif ($application->status === 'rejected' || $application->status === 'failed')
                    <div class="text-red-600 font-semibold mb-2">
                        ❌ Not valid to proceed.
                    </div>
                    <button class="auth-button auth-button-yellow opacity-50 cursor-not-allowed" disabled>
                        Proceed to Login
                    </button>
                @else
                    <div class="text-gray-600">
                        ⏳ Please wait for validation to complete.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>

