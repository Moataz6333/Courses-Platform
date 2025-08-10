<?php

namespace App\Http\Controllers;

use App\Http\Requests\CorrectRequest;
use App\Http\Resources\ExamResource;
use App\Http\Resources\ResultResource;
use App\Interfaces\AiInterface;
use App\Jobs\CorrectAnswer;
use App\Jobs\SendignEmailJob;
use App\Models\Exam;
use App\Models\Result;
use App\Service\GradingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use App\Events\ChangeProgressValue;
use App\Jobs\SendResultEmailJob;
use App\Models\Answer;

class ResultController extends Controller
{
    public $gradingService;
    public $ai;
    public function __construct(GradingService $gradingService, AiInterface $ai)
    {
        $this->gradingService = $gradingService;
        $this->ai = $ai;
    }
    public function index($examId)
    {

        $exam = Exam::findOrFail($examId)->load(['course']);
        Gate::authorize('CourseOwner', $exam->course);
        $rawGrades = Result::where('exam_id', $examId)
            ->select('grade', DB::raw('count(*) as count'))
            ->groupBy('grade')
            ->get()
            ->mapWithKeys(fn($item) => [$item->grade => $item->count])
            ->toArray();

        // Separate keys and values into arrays
        $grades = array_keys($rawGrades);  // ['A', 'B', 'C-', 'F']
        $counts = array_values($rawGrades); // [13, 5, 12, 31]
        $results = ResultResource::collection(Result::where('exam_id', $examId)->with(['user', 'answers'])->paginate(10));
        $allCorrected = false;
        if ($results->first()) {

            if (Result::where('exam_id', $examId)->where('status', 'pending')->first()) {
                if (DB::table('answers')->where('corrected_by', 'none')->whereIn('result_id', function ($query) use ($exam) {
                    $query->select('id')->from('results')->where('exam_id', $exam->id)->where('status', 'pending');
                })->count() == 0) {
                    $allCorrected=true;
                }
                return Inertia::render('Exams/correcting', [
                    'results' => ResultResource::collection(Result::where('exam_id', $examId)->where('status', 'pending')->with(['user', 'answers'])->paginate(10)),
                    'total' => DB::table('questions')->where('exam_id', $exam->id)->sum('points'),
                    'exam' => ExamResource::make($exam),
                    'grades' => $grades,
                    'allCorrected' => $allCorrected,
                ]);
            } else {
                return Inertia::render('Exams/results', [
                    'results' => $results,
                    'total' => $exam->questions->sum('points'),
                    'exam' => ExamResource::make($exam),
                    'grades' => $grades,
                    'counts' => $counts,
                    'info' => $this->info($examId),
                ]);
            }
        }
        return Inertia::render('Exams/results', [
            'results' => $results,
            'total' => $exam->questions->sum('points'),
            'exam' => ExamResource::make($exam),
            'grades' => $grades,
            'counts' => $counts,
            'info' => $this->info($examId)
        ]);
    }

    private function info($examId)
    {
        $results = Result::where('exam_id', $examId)->where('status', 'done')->get();
        $info = [];
        if (count($results) == 0) {
            $info = [
                'success' => 0,
                'fail' => 0,
                'aveTimeTaken' => 0,
                'conclusion' => 'There are no results yet'
            ];
            return $info;
        }

        $countsOfSuccess = $results->where('grade', '!=', 'F')->count();
        $info['success'] = round($countsOfSuccess / count($results) * 100);
        $info['fail'] = 100 - $info['success'];

        $info['aveTimeTaken'] = round($results->whereNotNull('timeTaken')->map(fn($item) => (int) $item->timeTaken)->avg());

        if ($info['success'] >= 90) {
            $info['conclusion'] = 'The exam is quite easy; many students got full marks quickly.';
        } elseif ($info['success'] >= 70 && $info['success'] < 90) {
            $info['conclusion'] = 'The exam is moderately easy; most students performed well.';
        } elseif ($info['success'] >= 40 && $info['success'] < 70) {
            $info['conclusion'] = 'The exam is balanced; there is a mix of strong and weak performance.';
        } elseif ($info['success'] < 40) {
            $info['conclusion'] = 'The exam is too hard for most students.';
        } elseif ($info['success'] < 40) {
            $info['conclusion'] = 'The exam might be tricky or misleading, students failed quickly.';
        } else {
            $info['conclusion'] = 'The exam results show unusual patterns. Please investigate further.';
        }
        return $info;
    }

    public function correct(CorrectRequest $request)
    {
        $result = Result::findOrFail($request->result_id)->load('user');
        $exam = Exam::findOrFail($result->exam_id)->load('course');
        Gate::authorize('CourseOwner', $exam->course);
        if ($result->answers->where('corrected_by', '!=', 'none')->first()) {
            foreach ($result->answers as $key => $answer) {
                if ($answer->corrected_by == 'ai') {
                    $result->correct = $result->correct - ($answer->ai_grade * $answer->question->points);
                    $answer->corrected_by = 'teacher';
                    $answer->ai_grade = $request->points[$key];
                    $answer->save();
                } else {
                    $result->correct = $result->correct - ($answer->ai_grade);
                    $answer->corrected_by = 'teacher';
                    $answer->ai_grade = $request->points[$key];
                    $answer->save();
                }
            }
            $result->correct += array_sum($request->points);
            if ($result->correct > $result->total) {
                return redirect()->back()->with('error', 'the correct points more than the total');
            }
            $result->grade = $this->gradingService->grade($result->total, $result->correct);
            $result->save();
        } else {
            $result->correct += array_sum($request->points);
            if ($result->correct > $result->total) {
                return redirect()->back()->with('error', 'the correct points more than the total');
            }
            $result->grade = $this->gradingService->grade($result->total, $result->correct);
            $result->status = 'done';
            $result->save();
            SendignEmailJob::dispatch('info@' . env('APP_NAME') . '.com', $result->user->email, "$exam->title Result!", "Dear {$result->user->name},\n\n" .
                "We hope this message finds you well. We are writing to inform you of your performance in the recent exam.\n\n" .
                "Your result:\n" .
                "- Correct Answers: {$result->correct} / {$result->total}\n" .
                "- Grade: {$result->grade}\n\n" .
                "Keep up the good work, and feel free to reach out to your instructor if you have any questions or need feedback.\n\n" .
                "Best regards,\n" .
                "Examination Committee");
        }

        return redirect()->route('exam.results', $exam->id)->with('success', 'result updated successfully');
    }
    public function ai_correct($examId)
    {
        $exam = Exam::findOrFail($examId)->load(['course']);
        Gate::authorize('CourseOwner', $exam->course);
        return Inertia::render('Exams/aiCorrecting', [
            'exam' => ExamResource::make($exam),
            'total' => DB::table('answers')->where('corrected_by', 'none')->whereIn('result_id', function ($query) use ($exam) {
                $query->select('id')->from('results')->where('exam_id', $exam->id)->where('status', 'pending');
            })->count(),
        ]);
    }
    public function ai_correct_start($examId)
    {
        $exam = Exam::findOrFail($examId)->load(['course']);
        Gate::authorize('CourseOwner', $exam->course);
        $results = Result::where('exam_id', $examId)->where('status', 'pending')->with('answers')->get();
        $totalAnswers = DB::table('answers')->where('corrected_by', 'none')->whereIn('result_id', function ($query) use ($exam) {
            $query->select('id')->from('results')->where('exam_id', $exam->id)->where('status', 'pending');
        })->count();
        foreach ($results as $result) {
            foreach ($result->answers as $answer) {
                CorrectAnswer::dispatch($answer, $totalAnswers);
            }
        }
        return redirect()->back()->with('success', 'Ai correcting start successfully');
    }
    public function send($examId) {
        $exam=Exam::findOrFail($examId)->load('course');
        Gate::authorize('CourseOwner',$exam->course);
        $results=Result::where('exam_id',$examId)->where('status','pending')->with(['user','answers'])->get();
        $total=$results->count();
        foreach ($results as $result) {
            $message='';
            if ($result->answers->where('corrected_by','ai')->first()) {
                foreach ($result->answers as $key => $answer) {
                    
                    $message .= ($key + 1).": " . " $answer->feedback\n";
                }
            }
            SendResultEmailJob::dispatch('info@' . env('APP_NAME') . '.com', $result->user->email, "$exam->title Result!", "Dear {$result->user->name},\n\n" .
                "We hope this message finds you well. We are writing to inform you of your performance in the recent exam.\n\n" .
                "Your result:\n" .
                "- Correct Answers: {$result->correct} / {$result->total}\n" .
                "- Grade: {$result->grade}\n\n" .
                "FeedBack: ".$message .
                "\nKeep up the good work, and feel free to reach out to your instructor if you have any questions or need feedback.\n\n" .
                "Best regards,\n" .
                "Examination Committee",$result,$total);
            }
            return redirect()->back()->with('success', 'Sending  start successfully');
    }
}
