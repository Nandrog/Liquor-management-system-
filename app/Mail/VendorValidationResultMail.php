<?php

namespace App\Mail;

use App\Models\VendorApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorValidationResultMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $application;

     public function __construct(VendorApplication $application)
    {

        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('Your Vendor Application Status')
                    ->view('emails.vendor_validation_result');
    }

}
