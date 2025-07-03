<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Faker\Provider\Lorem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all()->except([1, 5]);
        foreach ($courses as $course) {
            DB::table('lessons')->insert([
                [
                    'course_id'=>$course->id,
                    'title'=>'Lesson 1',
                    'description'=>Lorem::text(100)
                ],
                [
                    'course_id'=>$course->id,
                    'title'=>'Lesson 2',
                    'description'=>Lorem::text(100)
                ]
            ]);
        }
    }
}
