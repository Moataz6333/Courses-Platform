<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherWithCoursesResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $courses = [];
        foreach ($this->courses as $course) {
            array_push($courses, [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'track' => $course->track ? $course->track->name : '',
                'price' => $course->price == 0 ? 'free' : $course->price,
                'thumbnail' => $course->thumbnail ? $course->thumbnail->path : asset('storage/thumbnail.jpg'),
                'rate'=>floor($course->reviews->sum('votes')/$course->reviews->count('votes'))
            ]);
        }
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'specialization' => $this->specialization,
            'profile' => $this->user->profile ? $this->user->profile : asset('storage/profile.jfif'),
            'cover_photo' => $this->user->cover_photo ? $this->user->cover_photo : asset('storage/cover.jpg'),
            'courses' => $courses
        ];
    }
}
