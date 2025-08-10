<?php

use App\Events\ChangeEmailProgressValue;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardConroller;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SoicalMediaController;
use App\Http\Middleware\TeacherMiddleware;
use App\Jobs\SendignEmailJob;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\Offer;
use App\Models\Result;
use App\Models\User;
use App\Service\ResendEmailService;
use Faker\Provider\Lorem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Broadcast;



Broadcast::routes(['middleware' => ['auth:sanctum']]);
Route::middleware(['auth', TeacherMiddleware::class])->group(function () {
  Route::get('/', DashboardConroller::class)->name('home');
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
  Route::get('/results/{examId}', [ResultController::class, 'index'])->name('exam.results');
  Route::post('/correct', [ResultController::class, 'correct'])->name('result.correct');
  Route::get('/aiCorrect/{examId}', [ResultController::class, 'ai_correct'])->name('results.ai.correct');
  Route::post('/aiCorrect/start/{examId}', [ResultController::class, 'ai_correct_start'])->name('results.ai.start');
  Route::get('/send-to-sutends/{examId}', [ResultController::class, 'send'])->name('results.send');


  // question
  Route::post('question/create/{examId}', [ExamController::class, 'question'])->name('exams.question');
  Route::put('/questions/{questionId}', [QuestionController::class, 'update'])->name('questions.update');
  Route::resource('questions', QuestionController::class)->only(['edit', 'destroy']);
  // enrollments
  Route::get('/enrollments/{courseId}', [CourseController::class, 'enrollments'])->name('course.enrollments');
  // offers
  Route::get('/course/offers/{courseId}', [OfferController::class, 'index'])->name('course.offers');
  Route::resource('offers', OfferController::class)->except(['index','create']);
});
Route::get('test', function () {
  return url(asset('storage/logo.png'));
});
  
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
