<x-guest-layout>
    <div class="auth-card">
        <h2 class="text-lg font-bold">Set Your Password</h2>

        <form method="POST" action="{{ route('password.set.update', $user) }}">
            @csrf

            <div class="mt-4 relative">
                <label>Password</label>
                <input id="password" type="password" name="password" required class="input pr-10">
                <button type="button"
                    id="togglePassword"
                    class="absolute right-2 top-8 text-sm text-blue-600"
                    style="user-select:none;">
                    Show
                </button>
            </div>

            <div class="mt-4 relative">
                <label>Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="input pr-10">
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
