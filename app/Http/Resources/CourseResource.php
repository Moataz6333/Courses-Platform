<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'description'=>$this->description,
            'keyWords'=>$this->keyWords,
            'price'=>$this->price,
            'created_at'=>$this->created_at,
            'track_id'=>$this->track_id,
            'thumbnail'=>$this->thumbnail ? $this->thumbnail->path : ''
        ];
    }
}
