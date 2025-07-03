<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Review;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Testing\Fakes\Fake;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments=Enrollment::all();
        foreach ($enrollments as $enroll) {
            $review=Review::where('user_id',$enroll->user_id)->where('course_id',$enroll->course_id)->first();
            if(!$review){
                Review::create([
                    'user_id'=>$enroll->user_id,
                    'course_id'=>$enroll->course_id,
                    'votes'=>rand(1,5),
                    'content'=>fake()->name()
                ]);

            }
        }
    }
}
