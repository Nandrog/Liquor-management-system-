<?php

namespace App\Http\Controllers;

use App\Models\VendorApplication;
use Illuminate\Http\Request;

class VendorApplicationController extends Controller
{
    public function create()
    {
        return view('auth.vendor-application');
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'string', 'email', 'max:255', 'unique:vendor_applications'],
            'application_pdf' => ['required', 'file', 'mimes:pdf', 'max:2048'], // Max 2MB PDF
        ]);

        // Store the uploaded PDF
        $path = $request->file('application_pdf')->store('vendor-applications', 'public');

        VendorApplication::create([
            'vendor_name' => $request->vendor_name,
            'contact_email' => $request->contact_email,
            'pdf_path' => $path,
        ]);

        // Redirect back with a success message.
        // In a real app, an admin would review this and then invite the vendor to register.
        return redirect()->route('login')->with('status', 'Thank you! Your application has been submitted for review.');
    }
}
