<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Result;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Enrollment::where('course_id', 1)->paginate();
        foreach ($students as $student) {
            $arr=[0,10,25,20,35,45];
            $correct=$arr[array_rand($arr)];
            Result::create([
                'user_id'=>$student->user_id,
                'exam_id'=>3,
                'total'=>45,
                'correct'=>$correct,
                'hasWritten'=>false,
                'status'=>'done',
                'timeTaken'=>rand(1,20),
                'grade'=>$this->grade(45,$correct),
                'created_at'=>now()
            ]);
        }
    }
     private function grade($total, $correct)
    {

        $percentage = ($correct / $total) * 100;
        $grade = '';

        if ($percentage >= 90) {
            $grade = 'A';
        } elseif ($percentage >= 85) {
            $grade = 'A-';
        } elseif ($percentage >= 80) {
            $grade = 'B+';
        } elseif ($percentage >= 75) {
            $grade = 'B';
        } elseif ($percentage >= 70) {
            $grade = 'B-';
        } elseif ($percentage >= 65) {
            $grade = 'C+';
        } elseif ($percentage >= 60) {
            $grade = 'C';
        } elseif ($percentage >= 55) {
            $grade = 'C-';
        } else {
            $grade = 'F';
        }
        return $grade;
    }
}
