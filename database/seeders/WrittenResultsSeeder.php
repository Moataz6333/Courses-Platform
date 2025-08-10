<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Enrollment;
use App\Models\Result;
use Faker\Provider\Lorem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WrittenResultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Enrollment::where('course_id', 1)->get();
        foreach ($students as $student) {
            
            // $correct = rand(20,50);
            $result = Result::create([
                'user_id' => $student->user_id,
                'exam_id' => 8,
                'total' => 50,
                'correct' => 0,
                'hasWritten' => true,
                'status' => 'pending',
                'timeTaken' => rand(1, 20),
                'grade' => $this->grade(50, 0),
                'created_at' => now()
            ]);
            $answers = Answer::insert([
               [ 'user_id'=> $student->user_id,
                'question_id'=>32,
                'answer'=>Lorem::paragraph(),
                'result_id'=>$result->id,
                'created_at'=>now()
            ]

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
