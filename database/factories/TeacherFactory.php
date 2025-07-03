<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         $arr = ["Programming","Web Development","Mobile Development","Game Development","Data Science","Artificial Intelligence","Machine Learning","Cybersecurity","Computer Science","Engineering","Electrical Engineering","Mechanical Engineering","Civil Engineering","Software Engineering","Cloud Computing","DevOps","Database Administration","UI/UX Design","Graphic Design","Digital Marketing","SEO","Business","Entrepreneurship","Finance","Accounting","Economics","Mathematics","Statistics","Physics","Chemistry","Biology","Environmental Science","Psychology","Sociology","Philosophy","History","Law","Political Science","Languages","English","French","Spanish","Arabic","Chinese","Project Management","Human Resources","Education","Healthcare"];
        return [
            'user_id' => null,
            'description'=>"Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit sequi quis quod numquam repellat excepturi quidem asperiores maiores quisquam architecto! Quam consequuntur assumenda tempore perspiciatis iste quisquam, quo praesentium cum?",
            'national_id'=>rand(4444,9999),
            'phone'=>'+20122222222',
            'specialization'=>$arr[array_rand($arr)],
        ];
    }
}
