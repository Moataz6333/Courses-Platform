<?php

namespace App\Jobs;

use App\Events\ChangeEmailProgressValue;
use App\Interfaces\EmailInterface;
use App\Models\Result;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendResultEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
     public $to, $subject, $body, $from,$result,$total;

    public function __construct($from ,$to, $subject, $body,$result,$total)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->from = $from;
        $this->result = $result;
        $this->total = $total;
    }

   
   public function handle(): void
    {
         $emailService = app(EmailInterface::class);
         $emailService->send( $this->from ,$this->to, $this->subject, $this->body);
         $this->result->status='done';
         $this->result->save();
            
            $pending=Result::where('exam_id',$this->result->exam_id)->where('status','pending')->count();
            $progressValue=round((1-($pending/$this->total))*100);
         broadcast(new ChangeEmailProgressValue($this->result->exam_id,$progressValue));
    }
}
