<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FreeLessonsResource extends JsonResource
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
            'description'=>$this->description ?? '',
            'thumbnail'=>$this->thumbnail ? $this->thumbnail->path : '',
            'mediaType'=>$this->media ?$this->media->type :'',
        ];
    }
}
