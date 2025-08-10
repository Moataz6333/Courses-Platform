<?php

namespace App\Service;

use App\Events\ChangeProgressValue;
use App\Interfaces\AiInterface;
use App\Models\Result;
use Illuminate\Support\Facades\Http;

class AiCorrectingService implements AiInterface
{
      public function correct($answer, $model)
      {
            // $response = Http::post(env('AI_URL') . '/grade', [
            //       "student_answer" => $answer,
            //       "model_answer" => $model
            // ]);
            // return [
            //     'feedback'=>  $response['feedback'],
            //     'bert_score'=>  $response['scores']['bert_score'],
            // ];
            return [
                'feedback'=>  'this is the feedback',
                'bert_score'=>  0.890,
            ];
                 
      }
}
