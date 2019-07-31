<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserVerification extends Mailable
{
    use Queueable, SerializesModels;                // I don't know why.

    public $code, $full_name, $company_name;        // these attributes are accessable from mail template

    public function __construct($code, $full_name, $company_name)
    {
        $this->code = $code;
        $this->full_name = $full_name;
        $this->company_name = $company_name;
    }


    public function build()                         // auto call. Mail is sent from here
    {
        return $this->from('mirza@justanx.com', $this->company_name)
                    ->subject('Request for password reset')
                    ->markdown('mail.forgot-password');     // forgot-password is mail template under mail Directory.
    }
}
