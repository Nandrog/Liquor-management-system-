<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role; // Import the Role model

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {

        $isVendor = session('is_vendor_registration', false);

        // Prepare the data array to pass to the view.
        $data = [
            'is_vendor' => $isVendor,
            'factories' => Factory::orderBy('name')->get(),
            'name'      => '',
            'email'     => '',
        ];

        // If it is a vendor registration, add the pre-filled data.
        if ($isVendor) {
            $data['name'] = session('vendor_name');
            $data['email'] = session('contact_email');
        }

            // Fetch all factories from the database
    $factories = Factory::orderBy('name')->get();

    // Pass the factories to the view
    return view('auth.register', [
        'factories' => $factories,
        'data' => $data,
    ]);
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



    // Base validation rules
    $rules = [
        'firstname' => ['required', 'string', 'max:255'],
        'lastname' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'string', 'exists:roles,name'],
    ];

        // Conditional validation for supplier fields
if ($request->role === 'supplier') {
    $rules['location'] = ['required', 'string', 'max:255'];
    $rules['item_supplied'] = ['required', 'string', 'max:255'];
    $rules['phone'] = ['required', 'string', 'max:20'];
}

if ($request->role === 'customer') {
    $rules['city'] = ['required', 'string', 'max:255'];
    $rules['state'] = ['required', 'string', 'max:255'];
    $rules['phone_number'] = ['required', 'string', 'max:20'];
    $rules['location'] = ['required', 'string', 'max:255'];
}

 if ($request->role === 'vendor') {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['contact'] = ['required', 'string', 'max:20'];
        }
// The supplier record will be created after the user is created

    // Conditional validation for employee_id
    if (in_array($request->role, $employeeRoles)) {
        $rules['employee_id'] = ['required', 'string', 'max:255', 'unique:'.User::class];
    }

    // NEW: Conditional validation for factory_id
    if ($request->role === 'manufacturer') {
        $rules['factory_id'] = ['required', 'integer', 'exists:factories,id'];
    }

    $request->validate($rules);

    // Create the user, making sure to include the factory_id
    $user = User::create([
        'firstname' => $request->firstname,
        'lastname' => $request->lastname,
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'employee_id' => $request->employee_id,
        'factory_id' => $request->factory_id, // This will be null if not present
    ]);

    // CREATE THE SUPPLIER RECORD IF ROLE IS SUPPLIER
    if ($request->role === 'supplier') {
        \App\Models\Supplier::create([
            'user_id' => $user->id,
            'location' => $request->input('location'),
            'item-supplied' => $request->input('item_supplied'), // match your DB column name
            'phone' => $request->input('phone'),
        ]);
    }

    if ($request->role === 'customer') {
        \App\Models\Customer::create([
            'user_id' => $user->id,
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'phone_number' => $request->input('phone_number'),
            'location' => $request->input('location'),
        ]);
    }

    if ($request->role === 'vendor') {
        \App\Models\vendor::create([
            'user_id' => $user->id,
            'name' => $request->input('name'),
            'contact' => $request->input('contact'),
        ]);
    }

// ... assign role and redirect ...
$role = Role::findByName($request->role);
$user->assignRole($role);

event(new Registered($user));

return redirect(route('login'))->with('status', 'Registration successful! Please log in.');
    }
}
