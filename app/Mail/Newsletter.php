<?php

namespace App\Mail;

use App\User;
use App\Mails;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Newsletter extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;

    public function __construct(Mails $mail)
    {
        $this->mail = $mail;
    }

    public function build()
    { 
        return $this->from($this->mail->from)
                    ->subject($this->mail->subject)
                    ->view('vendor.voyager.newsletter.email');
    }
}
