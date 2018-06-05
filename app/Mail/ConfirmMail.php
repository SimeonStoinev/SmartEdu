<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build()
    {
        return $this->view('frontend.confirmMail')->subject('Потвърждаване в Smart Education');
    }
}
