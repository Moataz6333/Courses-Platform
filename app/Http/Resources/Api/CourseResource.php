<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $rate=0;
        if(count($this->reviews) != 0){
            $rate=floor($this->reviews->sum('votes')/$this->reviews->count('votes'));
            
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'track' => $this->track? $this->track->name : '',
            'price' => $this->price == 0 ? 'free' : $this->price,
            'thumbnail' => $this->thumbnail ? $this->thumbnail->path : asset('storage/thumbnail.jpg'),
            'rate'=>$rate,
            'teacher' => [
                'id'=>$this->teacher->id,
                'name' => $this->teacher->user->name,
                'specialization' => $this->teacher->specialization,
            ],
            'keyWords' => explode(' ',$this->keyWords),
        ];
    }
}
