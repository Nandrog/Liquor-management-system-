<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role; // Import the Role model

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Define which roles are considered 'employees' and require an employee_id
        $employeeRoles = ['manufacturer', 'procurement officer', 'liquor manager', 'finance'];

        // Base validation rules for all users
        $rules = [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'], // Validate that the role exists in the 'roles' table
        ];

        // Add conditional validation for employee_id only if the selected role requires it
        if (in_array($request->role, $employeeRoles)) {
            $rules['employee_id'] = ['required', 'string', 'max:255', 'unique:'.User::class];
        }

        // Run the validation
        $request->validate($rules);

        // Create the user with the validated data
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'employee_id' => $request->employee_id, // This will be null if not present in the request
        ]);

        // Find the role from the database and assign it to the newly created user
        $role = Role::findByName($request->role);
        $user->assignRole($role);

        // Fire the 'Registered' event for things like sending verification emails
        event(new Registered($user));

        // As requested, redirect to the login page with a success message.
        // We are NOT logging the user in automatically.
        return redirect(route('login'))->with('status', 'Registration successful! Please log in.');
    }
}
