<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use App\Models\VendorApplication;
use App\Mail\VendorValidationResultMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
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
            'application_pdf' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        // Store the uploaded PDF
        $pdf = $request->file('application_pdf');
        $pdfPath = $pdf->store('pdfs', 'public');

        $application = VendorApplication::create([
            'vendor_name' => $request->vendor_name,
            'contact_email' => $request->contact_email,
            'pdf_path' => $pdfPath,
        ]);

        // Convert PDF to base64 and send to Java server
        $pdfFullPath = Storage::disk('public')->path($pdfPath);
        $pdfBase64 = base64_encode(file_get_contents($pdfFullPath));

        $result = null;

        $response = Http::post(config('services.vendor_validation.url'), [
            'vendor_name' => $request->vendor_name,
            'pdf_base64' => $pdfBase64,
        ]);

        if ($response->ok()) {
            $result = $response->json();
            \Log::info('Java server response:', $result);

            $application->status = (string) ($result['status'] ?? 'pending');
            $application->visit_scheduled_for = $result['scheduled_visit'] ?? null;
            $application->validation_notes = $result['reason'] ?? null;
        } else {
            \Log::error('Java server error:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            $application->status = 'pending';
        }

        $application->save();

        try {
            Mail::to($application->contact_email)->send(new VendorValidationResultMail($application));
        } catch (\Exception $e) {
            \Log::error('Email failed: ' . $e->getMessage());
        }

        // ✅ Validation passed
  // --- REPLACE WITH THIS BLOCK ---
        if (!empty($result) && in_array($result['status'], ['approved', 'passed'])) {
    
    // Redirect the user to the dedicated vendor registration form,
    // passing the approved application's ID in the URL.
    return redirect()->route('vendor.registration.create', ['application' => $application->id]);
}

        // ❌ Not approved: show result page
        return view('auth.vendor-application-result', [
            'application' => $application,
        ]);
    }
}
