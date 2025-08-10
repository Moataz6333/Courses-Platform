<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'question'=>$this->question->head,
            'points'=>$this->question->points,
            'answer'=>$this->answer,
            'corrected_by'=>$this->corrected_by,
            'ai_grade'=>$this->ai_grade ? $this->ai_grade *100  : '',
            'grade'=> $this->ai_grade ? $this->ai_grade*$this->question->points : ''
        ];
    }
}
