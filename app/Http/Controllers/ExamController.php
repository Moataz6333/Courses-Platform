<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\ExamResource;
use App\Http\Resources\QuestionResource;
use App\Interfaces\MediaInterface;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Media;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public $mediaService;
    public function __construct(MediaInterface $mediaService)
    {
        $this->mediaService = $mediaService;
    }
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        Gate::authorize('CourseOwner', $course);
        return Inertia::render('Exams/index', [
            'course' => CourseResource::make($course),
            'exams' => ExamResource::collection($course->exams)
        ]);
    }


    public function create($courseId)
    {
        $course = Course::findOrFail($courseId);
        Gate::authorize('CourseOwner', $course);
        return Inertia::render('Exams/create', [
            'course' => CourseResource::make($course)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamRequest $request, $courseId)
    {
        $exam = Exam::create([
            'course_id' => $courseId,
            'title' => $request->title,
            'period' => (int) $request->period,
            'startDate' => $request->startDate,
        ]);
        return redirect()->route('exams.show', $exam->id)->with('success', 'exam created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $exam = Exam::findOrFail($id)->load(['course','questions']);
        Gate::authorize('CourseOwner', $exam->course);
        return Inertia::render('Exams/show', [
            'exam' => ExamResource::make($exam),
            'questions' => QuestionResource::collection($exam->questions->load('options'))
        ]);
    }

    public function question(StoreQuestionRequest $request, $examId)
    {
        if (!$request->hasFile('media') && $request->questionHead=='') {
            return redirect()->back()->with('error','The question head is required');
        }
        //    has options 
        if ($request->hasOptions) {
            $question =  Question::create([
                'exam_id' => $examId,
                'head' => $request->questionHead ?? " ",
                'points' => (int) $request->points,
                'correct_ans' => $request->options['correct' === true]['answer'],
                'has_options' => $request->hasOptions,
            ]);
            foreach ($request->options as $option) {
                Option::create([
                    'question_id' => $question->id,
                    'answer' => $option['answer'],
                    'isCorrect' => $option['correct'],
                ]);
            }
        } else {
            $question=    Question::create([
                'exam_id' => $examId,
                'head' => $request->questionHead ?? " ",
                'points' => (int)  $request->points,
                'correct_ans' => $request->answer,
                'has_options' => $request->hasOptions,
            ]);
        }
        // media
        if($request->hasFile('media')){
            try {
                    $uploaded = $this->mediaService->upload($request->file('media')->getRealPath(), $question->exam->course_id);
                   
                    Media::create([
                        'question_id'=>$question->id,
                        'public_id' => $uploaded['public_id'],
                        'resource_type' => $uploaded['resource_type'],
                        'path' => $uploaded['secure_url'],
                        'type' => 'image',
                    ]);
                
                } catch (\Exception $ex) {
                   return   redirect()->route('exams.show', $examId)->with('error', $ex->getMessage());
                }
        }

        return redirect()->route('exams.show', $examId)->with('success', 'question added successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $exam = Exam::findOrFail($id);
        Gate::authorize('CourseOwner', $exam->course);
        return Inertia::render('Exams/edit', [
            'exam' => ExamResource::make($exam)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExamRequest $request, $id)
    {
        $exam = Exam::findOrFail($id)->load('course');
        Gate::authorize('CourseOwner', $exam->course);
        $exam->update($request->validated());
        return redirect()->route('exams.edit', $exam->id)->with('success', 'exam updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $exam = Exam::findOrFail($id)->load('course');
        Gate::authorize('CourseOwner', $exam->course);
        $courseId = $exam->course_id;
        $exam->delete();
        return redirect()->route('exams.index', $courseId)->with('success', 'exam delete successfully!');
    }
}
