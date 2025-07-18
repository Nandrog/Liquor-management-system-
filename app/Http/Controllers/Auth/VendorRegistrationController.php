<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class VendorRegistrationController extends Controller
{
    /**
     * Show the vendor registration form, pre-filled with application data.
     */
    public function create(VendorApplication $application)
    {
        // Ensure the application has been approved by the Java server.
        if (!in_array($application->status, ['approved', 'passed'])) {
            abort(404, 'Application not found or not yet approved.');
        }

        // Optional: Check if a user has already been created for this email.
        if (User::where('email', $application->contact_email)->exists()) {
            // Redirect them to the login page with a message.
            return redirect()->route('login')->with('error', 'An account for this email already exists. Please log in.');
        }

        return view('auth.vendor-registration', ['application' => $application]);
    }

    /**
     * Handle the registration form submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'application_id' => ['required', 'integer', 'exists:vendor_applications,id'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'contact' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $application = VendorApplication::findOrFail($request->application_id);

        // Use a database transaction to ensure all records are created successfully.
        $user = DB::transaction(function () use ($request, $application) {
            // 1. Create the User record for authentication.
            $newUser = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $application->contact_email, // Get email from the application record.
                'password' => Hash::make($request->password),
            ]);

            // Assign the 'Vendor' role to the new user.
            $newUser->assignRole('Vendor');

            // 2. Create the Vendor profile record with business-specific data.
            Vendor::create([
                'user_id' => $newUser->id,
                'name' => $application->vendor_name, // Get company name from the application.
                'contact' => $request->contact,
            ]);
            
            return $newUser;
        });

        // 3. Log the newly created user in.
        Auth::login($user);

        // 4. Redirect them to their dashboard.
        return redirect()->route('dashboard');
    }
}

