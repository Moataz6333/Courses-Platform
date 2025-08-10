<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'student')->paginate(20);
        $course = Course::find(86);
        foreach ($users as $user) {
            // foreach ($courses as $course) {
                $enroll = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->first();
                if (!$enroll) {
                    Enrollment::create([
                        'user_id'=>$user->id,
                        'course_id'=>$course->id,
                        'courseType'=>'free',
                        'paid'=>true,
                        'created_at'=>now()
                    ]);
                }
            // }
        }
    }
}
