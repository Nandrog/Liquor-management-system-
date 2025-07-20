<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthFacade; // Use an alias to avoid conflict
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class VendorRegistrationController extends Controller
{

    public function create(Request $request)
    {
        // Find the application using the ID from the validated signed URL.
        $application = VendorApplication::findOrFail($request->application);

        // Optional but recommended: Check if a user has already registered with this email.
        if (User::where('email', $application->contact_email)->exists()) {
            return redirect()->route('login')->with('status', 'An account for this email already exists. Please log in.');
        }

        return view('auth.vendor-registration',['application' => $application]);
    }

    /**
     * Handle the registration form submission, creating the User and Vendor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => ['required', 'integer', 'exists:vendor_applications,id'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username'       => ['required', 'string', 'max:255', 'unique:users,username'],
            'contact'        => ['required', 'string', 'max:255'],
            'password'       => ['required', 'confirmed', Password::defaults()],
        ]);

        $application = VendorApplication::findOrFail($validated['application_id']);

        // Final security check: has this application already been completed?
        if ($application->status === 'completed') {
            return redirect()->route('login')->with('status', 'This application has already been used to register an account.');
        }

        $user = DB::transaction(function () use ($validated, $application) {
            // 1. Create the User record for authentication.
            $newUser = User::create([
                'firstname'     => $validated['firstname'],
                'lastname'      => $validated['lastname'],
                'username' => $validated['username'],
                'email'    => $application->contact_email, // Get email from the secure application record.
                'password' => Hash::make($validated['password']),
            ]);

            // Assign the 'Vendor' role to the new user.
            $newUser->assignRole('Vendor');
             $vendorFullName = $validated['firstname'] . ' ' . $validated['lastname'];

            // 2. Create the Vendor profile record.
            Vendor::create([
                'user_id'      => $newUser->id,
                'name'         => $vendorFullName,
                'company_name' => $application->vendor_name, // Get company name from the application.
                'contact'      => $validated['contact'], // You might want more vendor fields here
            ]);

            // 3. Mark the application as completed to prevent reuse.
            $application->update(['status' => 'completed']);

            return $newUser;
        });

        // 4. Log the newly created user in.
        AuthFacade::login($user);

        // 5. Redirect them to their dashboard.
        return redirect()->route('vendor.dashboard')->with('success', 'Registration complete! Welcome aboard.');
    }
}
