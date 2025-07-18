<x-guest-layout>

    @php
        $employeeRoles = ['finance', 'supplier', 'manufacturer', 'liquor manager', 'procurement officer'];
        $currentRole = request()->get('role', 'vendor');
        $isEmployee = in_array($currentRole, $employeeRoles);

    @endphp

    <div class="auth-card">
        <h2 class="text-lg font-bold">Set Your Password</h2>

        <form method="POST" action="{{ route('password.set.update', $user) }}">
            @csrf
                    {{-- Hidden input to pass the role to the controller --}}
                    <input type="hidden" name="role" value="{{ $user->getRoleNames()->first() }}">

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

                    <div class="mt-4">
                        <label for="name">Full Name</label>
                        <input class="auth-input" id="name" type="text" name="name" required class="block mt-1 w-full" value="{{ old('name') }}">
                    </div>

                    <div class="mt-4">
                        <label for="phone_number">Phone</label>
                        <input class="auth-input" id="contact" type="text" name="contact" required class="block mt-1 w-full" value="{{ old('contact') }}">
                    </div>

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
            <div class="mt-4 relative">
                <label>Password</label>
                <input class="auth auth-input" id="password" type="password" name="password" required class="input pr-10">
                <button type="button"
                    id="togglePassword"
                    class="absolute right-2 top-8 text-sm text-blue-600"
                    style="user-select:none;">
                    Show
                </button>
            </div>

            <div class="mt-4 relative">
                <label>Confirm Password</label>
                <input class="auth auth-input" id="password_confirmation" type="password" name="password_confirmation" required class="input pr-10">
                <button type="button"
                    id="togglePasswordConfirmation"
                    class="absolute right-2 top-8 text-sm text-blue-600"
                    style="user-select:none;">
                    Show
                </button>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn btn-primary">Set Password</button>
            </div>
        </form>
    </div>

    <script>
        function toggleVisibility(buttonId, inputId) {
            const btn = document.getElementById(buttonId);
            const input = document.getElementById(inputId);

            btn.addEventListener('click', () => {
                if (input.type === "password") {
                    input.type = "text";
                    btn.textContent = "Hide";
                } else {
                    input.type = "password";
                    btn.textContent = "Show";
                }
            });
        }

        toggleVisibility('togglePassword', 'password');
        toggleVisibility('togglePasswordConfirmation', 'password_confirmation');
    </script>
</x-guest-layout>
