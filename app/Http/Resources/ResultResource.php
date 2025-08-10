<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $answers=[];
        if ($this->answers) {
            $answers=AnswerResource::collection($this->answers);
        }
        return [
            'id'=>$this->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'correct' => $this->correct,
            'status' => $this->status,
            'timeTaken' => $this->timeTaken,
            'grade' => $this->grade,
            'answers'=>$answers,
            'created_at'=>date_create($this->created_at)->format('h:i a M-Y')
        ];
    }
}
