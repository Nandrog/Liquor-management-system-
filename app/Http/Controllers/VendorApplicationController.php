<?php

namespace App\Http\Controllers;

use App\Models\VendorApplication;
use App\Mail\VendorValidationResultMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

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
        $pdf = $request->file('application_pdf');
        $pdfPath = $pdf->store('pdfs', 'public');

        // Redirect back with a success message.
        // In a real app, an admin would review this and then invite the vendor to register.
        //return redirect()->route('login')->with('status', 'Thank you! Your application has been submitted for review.');*/

        $application = VendorApplication::create([
            'vendor_name' => $request->vendor_name,
            'contact_email' => $request->contact_email,
            'pdf_path' => $pdfPath,
        ]);

       // Send PDF to Java server for validation
       $pdfFullPath = Storage::disk('public')->path($pdfPath);
       $pdfBase64 = base64_encode(file_get_contents($pdfFullPath));

       $result = null;

        //send base64 pdf to java server
        $response = Http::post(config('services.vendor_validation.url'), [
            'vendor_name' => $request->vendor_name,
            'pdf_base64' => $pdfBase64,
        ]);

        if ($response->ok()) {
         $result = $response->json();
         \Log::info('Java server response:', $result);

         $application->status = (string) $result['status'] ?? 'pending';
         $application->visit_scheduled_for = $result['scheduled_visit'] ?? null;
         $application->validation_notes = $result['reason'] ?? null;

        } else {
            \Log::error('Java server error:', ['status' => $response->status(), 'body' => $response->body()]);
            $application->status  = 'pending';
        }

        $application->save();

         // Send email to the vendor
         try {
            Mail::to($application->contact_email)->send(new VendorValidationResultMail($application));
            } catch (\Exception $e) {
                \Log::error('Email failed: ' . $e->getMessage());
            }

        //Auto create vendor if approved
        if(!empty($result) && ($result['status'] === 'approved' || $result['status'] === 'passed')) {
            \App\Models\Vendor::create([
                'name' => $request->vendor_name,
                'contact' => $request->contact_email,
            ]);
        }

       \Log::info('Returning vendor application result view.', ['status' => $application->status]);

       return view('auth.vendor-application-result', [
            'application' => $application
       ]);

    }
}
