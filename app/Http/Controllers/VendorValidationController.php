<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VendorValidationController extends Controller
{
    public function showForm()
    {
        return view('vendor.apply');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'vendor_name' => 'required|string',
            'application_pdf' => 'required|mimes:pdf|max:10240', // 10MB
        ]);

        // Encode PDF to base64
        $pdf = $request->file('application_pdf');
        $pdfContent = base64_encode(file_get_contents($pdf->getRealPath()));

        //prepare payload
        $payload = [
            'vendor_name' => $request->vendor_name,
            'pdf_base64' => $pdfContent,
        ];

        // Send POST request to Java server
        $response = Http::post('http://localhost:8080/api/validate-vendor', $payload);

        // Handle response
        if ($response->successful()) {
            $result = $response->json();
            return back()->with('message', 'Validation ' . $result['status'] . '. Visit scheduled: ' . ($result['scheduled_visit'] ?? 'N/A'));
        } else {
            return back()->withErrors(['Validation server error.']);
        }
    }
}
