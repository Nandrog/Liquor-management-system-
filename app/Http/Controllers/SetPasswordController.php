<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SetPasswordController extends Controller
{
    public function show(Request $request, User $user)
    {
        return view('auth.set-password', compact('user'));
    }

    public function update(Request $request, User $user)
    {
         $rules = [
        'firstname' => ['required', 'string', 'max:255'],
        'lastname' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'role' => ['required', 'string', 'exists:roles,name'],
    ];
        // Conditional validation for vendor fields
        if ($request->role === 'vendor') {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['contact'] = ['required', 'string', 'max:20'];
        }

        $request->validate($rules);

        // Update user details
        $user->update([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
        ]);
        // If the user is a vendor, update vendor details
        if ($request->role === 'vendor') {
            $user->vendor()->updateOrCreate(
                [
                    'name' => $request->input('name'),
                    'contact' => $request->input('contact'),
                ]
            );
        }
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('vendor.dashboard')->with('success', 'Password set! Welcome to your dashboard.');
    }
}
