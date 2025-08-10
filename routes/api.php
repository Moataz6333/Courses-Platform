<?php

use App\Http\Controllers\Api\CoursesController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Middleware\StudentMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [UserController::class,'login']);
Route::post('register', [UserController::class,'register']);
// google
Route::get('/auth/google', [UserController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [UserController::class, 'handleGoogleCallback']);

 // courses
    Route::get('courses', [CoursesController::class,'index']);
    Route::get('course/{id}', [CoursesController::class,'course']);

    // teachers
    Route::get('/teachers',[TeacherController::class, 'index']);
    Route::get('/teacher/{id}',[TeacherController::class, 'teacher']);


Route::middleware(['auth:sanctum',StudentMiddleware::class])->group(function () {
    Route::get('/me', [UserController::class,'me']);
    // enroll
    Route::get('enroll/{courseId}', [CoursesController::class,'enroll']);
    Route::get('/myCourses', [CoursesController::class,'myCourses']);
    Route::get('/myCourse/{id}', [CoursesController::class,'myCourse']);
    // exam
    Route::get('/exam/{id}', [ExamController::class,'viewExam']);
    Route::get('/exam/start/{id}', [ExamController::class, 'start']);
    Route::post('/submitExam/{id}',[ExamController::class, 'submit']);

    // reviews
    Route::post('/course/review/{courseId}', [ReviewController::class, 'store']);
    Route::post('/update/review/{reviewId}', [ReviewController::class, 'update']);
    Route::delete('/course/review/{id}', [ReviewController::class, 'destroy']);

    // lessons
    Route::get('/lesson/{id}', [CoursesController::class,'lesson']);
    

});
