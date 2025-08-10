<?php

namespace App\Jobs;

use App\Console\Commands\ChangeProgress;
use App\Events\ChangeProgressValue;
use App\Interfaces\AiInterface;
use App\Models\Result;
use App\Service\GradingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class CorrectAnswer implements ShouldQueue
{
    use Queueable;

    public $answer;
    public $total;
    public function __construct($answer, $total)
    {
        $this->answer = $answer;
        $this->total = $total;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $aiCorrectingService = app(AiInterface::class);
        $response = $aiCorrectingService->correct($this->answer->answer, $this->answer->question->head);
        $this->answer->corrected_by = 'ai';
        $this->answer->feedback = $response['feedback'];
        $aiScore = $response['bert_score'];
        $this->answer->ai_grade = $aiScore;
        $result = Result::findOrfail($this->answer->result_id);
        // correct result
        if ($aiScore >= 0.8) {
            $result->correct += $this->answer->question->points;
        } else {
            $result->correct += $aiScore * $this->answer->question->points;
        }
        $gradService = new GradingService();
        $result->grade = $gradService->grade($result->total, $result->correct);
        $result->save();
        $this->answer->save();
        $totalAnswers = DB::table('answers')->where('corrected_by', 'none')->whereIn('result_id', function ($query) use ($result) {
            $query->select('id')->from('results')->where('exam_id', $result->exam_id)->where('status', 'pending');
        })->count();
            $progressValue=round((1-($totalAnswers/$this->total))*100);
        broadcast(new ChangeProgressValue($result->exam_id,$progressValue,$totalAnswers));


    }
}
