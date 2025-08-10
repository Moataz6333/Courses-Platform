<?php

namespace App\Jobs;

use App\Interfaces\EmailInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendignEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $to, $subject, $body, $from;

    public function __construct($from ,$to, $subject, $body )
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->from = $from;
    }

    /**
     * Execute the job.
     */
    public function handle(EmailInterface $emailService): void
    {
        $emailService->send( $this->from ,$this->to, $this->subject, $this->body);
    }
}
