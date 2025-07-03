<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SoicalMediaController;
use App\Http\Middleware\TeacherMiddleware;
use App\Models\Exam;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;





Route::middleware(['auth', TeacherMiddleware::class])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('home');
    // cources
    Route::resource('courses', CourseController::class)->except('upload');
    Route::post('courses/update/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::resource('socialmedia', SoicalMediaController::class)->only(['index', 'store']);
    // lessons
    Route::get('lessons/course/{courseId}', [LessonsController::class, 'index'])->name('lessons.index');
    Route::post('lessons/course/{courseId}', [LessonsController::class, 'store'])->name('lessons.store');
    Route::post('lessons/{lessonId}', [LessonsController::class, 'update'])->name('lessons.update');
    Route::get('lesson/media/{id}', [LessonsController::class, 'media'])->name('lessons.media');
    Route::post('media/{lessonId}', [LessonsController::class, 'storeMedia'])->name('lessons.storeMedia');
    Route::delete('media/{lessonId}', [LessonsController::class, 'deleteMedia'])->name('lessons.deleteMedia');
    Route::resource('lessons', LessonsController::class)->only(['edit', 'destroy']);
    // exams
    Route::resource('exams', ExamController::class)->only(['edit', 'update', 'destroy']);
    Route::get('exams/create/{courseId}', [ExamController::class, 'create'])->name('exams.create');
    Route::get('exams/{id}/show', [ExamController::class, 'show'])->name('exams.show');
    Route::get('exams/{courseId}', [ExamController::class, 'index'])->name('exams.index');
    Route::post('exams/{courseId}', [ExamController::class, 'store'])->name('exams.store');
    // question
    Route::post('question/create/{examId}', [ExamController::class, 'question'])->name('exams.question');
    Route::put('/questions/{questionId}', [QuestionController::class, 'update'])->name('questions.update');
    Route::resource('questions', QuestionController::class)->only(['edit', 'destroy']);
    // enrollments
    Route::get('/enrollments/{courseId}', [CourseController::class, 'enrollments'])->name('course.enrollments');
});
Route::get('test', function () {
 $exam=Exam::findOrFail(3)->load('questions');
 return $exam->questions->where('id',9)->first();

});
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
