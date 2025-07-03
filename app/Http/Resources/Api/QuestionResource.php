<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $options=[];
        foreach ($this->options as $option) {
            array_push($options,['id'=>$option->id,'answer'=>$option->answer]);
        }
        return [
            'id'=>$this->id,
            'head'=>$this->head,
            'photo'=>$this->media ? $this->media->path : '',
            'has_options'=>$this->has_options,
            'options'=>$options,
        ];
    }
}
