<?php

namespace App\Providers;

use App\Interfaces\AiInterface;
use App\Interfaces\EmailInterface;
use App\Interfaces\MediaInterface;
use App\Interfaces\PaymentInterface;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Review;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Service\AiCorrectingService;
use App\Service\CloudinaryMediaService;
use App\Service\MyFatoorahPaymentService;
use App\Service\ResendEmailService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MediaInterface::class, function () {
            return new CloudinaryMediaService();
        });
        $this->app->bind(PaymentInterface::class, function () {
            return new MyFatoorahPaymentService();
        });
        $this->app->bind(EmailInterface::class, function () {
            return new ResendEmailService();
        });
       $this->app->bind(AiInterface::class, AiCorrectingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('isTeacher', function (User $user) {
            return $user->role === 'teacher';
        });
        Gate::define('isStudent', function (User $user) {
            return $user->role === 'student';
        });
        // has own course
        Gate::define('CourseOwner', function (User $user, Course $course) {
            return $user->teacher->id === $course->teacher_id;
        });
        // not enrolled before
        Gate::define('EnrollendBefore', function (User $user, Course $course) {
            $enrollment = Enrollment::where('course_id', $course->id)->where('user_id', $user->id)->first();
            if ($enrollment) {
                return false;
            }
            return true;
        });
        // enrolled to course
        Gate::define('isEnrolled',function (User $user, Course $course)  {
            $enrollment = Enrollment::where('course_id', $course->id)->where('user_id', $user->id)->first();
            if ($enrollment) {
                return $enrollment->paid;
            }
            return false;
        });
         // not enrolled before
        Gate::define('ReviewedBefore', function (User $user, Course $course) {
            $enrollment = Review::where('course_id', $course->id)->where('user_id', $user->id)->first();
            if ($enrollment) {
                return false;
            }
            return true;
        });
        Gate::define('OwnReview',function (User $user,Review $review)  {
            return $user->id === $review->user_id;
        });
        Gate::define('SubmittedBefore',function (User $user,Exam $exam) {
             $result = Result::where('exam_id', $exam->id)->where('user_id', $user->id)->first();
            if ($result) {
                return false;
            }
            return true;
        });
    }
}
