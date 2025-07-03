<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->has_options) {
            return [
            'id'=>$this->id,
            'head'=>$this->head,
            'points'=>$this->points,
            'correct_ans'=>$this->correct_ans,
            'exam_id'=>$this->exam_id,
            'has_options'=>$this->has_options,
            'options'=>OptionResourse::collection($this->options),
            'media'=>$this->media ? [
                'path'=>$this->media->path,
            ]:'',
        ];
        } else {
            return [
            'id'=>$this->id,
            'head'=>$this->head,
            'points'=>$this->points,
            'answer'=>$this->answer,
            'exam_id'=>$this->exam_id,
            'correct_ans'=>$this->correct_ans,
            'has_options'=>$this->has_options,
             'media'=>$this->media ? [
                'path'=>$this->media->path,
            ]:'',
        ];
        }
        
    }
}
