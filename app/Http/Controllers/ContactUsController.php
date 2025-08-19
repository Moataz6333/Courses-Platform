<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Interfaces\EmailInterface;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public $emailService;
    public function __construct(EmailInterface $emailService) {
        $this->emailService = $emailService;
    }
    public function send(ContactUsRequest $request) {
        // $this->emailService->send($request->email,'motazahmed0155@gmail.com',$request->subject,$request->message);
        return response()->json(['message'=>'Email sended successfully'], 200);
    }
}
