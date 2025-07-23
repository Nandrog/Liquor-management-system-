<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportDashboardController extends Controller
{
    /**
     * Display the main reports dashboard.
     */
    public function index()
    {
        // We can add a check here to ensure only authorized roles can even see this page
        if (!Auth::user()->hasAnyRole(['Admin', 'Finance', 'Liquor Manager', 'Procurement Officer', 'Supplier', 'Manufacturer'])) {
            abort(403, 'Unauthorized Access');
        }

        return view('reports.index');
    }
}
