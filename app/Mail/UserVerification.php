<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $code, $full_name;

    public function __construct($code, $full_name)
    {
        $this->code = $code;
        $this->full_name = $full_name;
    }


    public function build()
    {
        return $this->from('mirza@justanx.com', 'We Are X')
                    ->subject('Request for password reset')
                    ->markdown('mail.forgot-password');
    }
}
