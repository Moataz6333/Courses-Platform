<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseWithLessonsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lessons = [];
        if ($this->price == 0) {
            $lessons = FreeLessonsResource::collection($this->lessons);
        } else {
            // not free
            $lessons = PaidLessonsResource::collection($this->lessons);
        }


        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price == 0 ? 'free' : $this->price,
            'lessons_count' => count($this->lessons),
            'exams_count' => count($this->exams),
            'exams' => $this->exams->map(function ($exam) {
                return [
                    'id' => $exam->id,
                    'title' => $this->title
                ];
            }),
            'teacher' => [
                'name' => $this->teacher->user->name,
                'description' => $this->teacher->description,
                'specialization' => $this->teacher->specialization,
            ],
            'lessons' => $lessons,
            'reviews' => ReviewResource::collection($this->reviews->load('user'))
        ];
    }
}
