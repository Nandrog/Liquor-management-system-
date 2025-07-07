<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        auth()->login($user);

        return redirect()->route('vendor.dashboard')->with('success', 'Password set! Welcome to your dashboard.');
    }
}
