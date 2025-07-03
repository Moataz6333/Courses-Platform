<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ExamResource;
use App\Http\Resources\Api\QuestionResource;
use App\Models\Exam;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ExamController extends Controller
{
    public function viewExam($id)
    {
        $exam = Exam::withCount('questions')->findOrFail($id);
        Gate::authorize('isEnrolled', $exam->course);
        return response()->json(['exam' => ExamResource::make($exam)], 200);
    }
    public function start($id)
    {
        $exam = Exam::withCount('questions')->findOrFail($id);
        Gate::authorize('isEnrolled', $exam->course);
        return response()->json(['exam' => ExamResource::make($exam), 'questions' => QuestionResource::collection($exam->questions)], 200);
    }
    public function submit(Request $request, $id)
    {
        $exam = Exam::findOrFail($id)->load('questions');
        Gate::authorize('SubmittedBefore');
        $res = new Result();
        $res->user_id = Auth::user()->id;
        $res->exam_id = $exam->id;
        $res->total = $exam->questions->sum('points');
        $res->hasWritten = false;
        $correct = 0;
        $numberOfCorrectAns = 0;
        //   options correcting
        foreach ($request->answers as $answer) {
            $question = $exam->questions->where('id', $answer['id'])->first();
            if ($question && $question->has_options) {
                // option
                $op = $question->options->where('id', $answer['answer'])->first();
                if ($op && $op->isCorrect) {
                    $correct += (int) $question->points;
                    $numberOfCorrectAns++;
                }
            }
            if (!$question->has_options) {
                $res->hasWritten = true;
            }
        }
        $res->correct = $correct;
        $res->grade=$this->grade($res->total,$correct);
        if (!$res->hasWritten) {
            $res->status = 'done';
            $res->save();
            return response()->json([
                'message' => 'exam submitted successfully',
                'result' => [
                    'points' => "$correct/$res->total",
                    'correct_questions' => "$numberOfCorrectAns",
                    'grade'=>$res->grade
                ]
            ], 200);
        } else {
            $res->status = 'pending';
              $res->save();
              return response()->json([
                'message' => 'exam submitted successfully, The Written questions will be corrected by the teacher and you will get an email for the results',
            ], 200);
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
