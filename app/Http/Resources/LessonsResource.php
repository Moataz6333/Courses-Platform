<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonsResource extends JsonResource
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
            'course_id'=>(int) $this->course_id,
            'title'=>$this->title,
            'description'=>$this->description ?? '',
            'mediaType'=>$this->media?$this->media->type : '',
            'media'=>$this->media ? $this->media->path : '',
            'public_id'=>$this->media ? $this->media->public_id : '',
            'thumbnail'=>$this->thumbnail ? $this->thumbnail->path : '',
        ];
    }
}
