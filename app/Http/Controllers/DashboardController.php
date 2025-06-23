<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Use __invoke for single-action controllers
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('Supplier')) {
            return redirect()->route('supplier.dashboard');
        }

        if ($user->hasRole('Liquor Manager')) {
            return redirect()->route('manager.dashboard');
        }

        // Add other role checks here...

        // Fallback for users with no specific dashboard or for a default role
        return view('dashboard'); // The default Breeze dashboard
    }
}
