<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeachersResourse extends JsonResource
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
            'name'=>$this->user->name,
            'email'=>$this->user->email,
            'specialization'=>$this->specialization,
            'profile'=>$this->user->profile ?$this->user->profile : asset('storage/cover.jpg'),
            'courses_count'=>$this->courses_count
        ];
    }
}
