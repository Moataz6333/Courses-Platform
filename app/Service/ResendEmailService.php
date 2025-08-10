<?php

namespace App\Service;

use App\Interfaces\EmailInterface;
use Resend\Laravel\Facades\Resend ;

class ResendEmailService implements EmailInterface
{
    
    public function send($from,$to, $subject, $message)
    {
        $html = view('emails.message', [
            'from' => $from,
            'to' => $to,
            'subject' => 'Welcome to our service!',
            'body' =>$message
        ])->render();
       Resend::emails()->send([
            'from' => 'Acme <onboarding@resend.dev>',
            'to' => ['moatazahmedghander2003@gmail.com'],
            'subject' => $subject,
            'html' => $html,
        ]);
        return true;
    }
}
