<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CourseResource;
use App\Http\Resources\Api\CourseWithLessonsResource;
use App\Http\Resources\Api\FreeLessonsResource;
use App\Interfaces\PaymentInterface;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Asset\Media;


class CoursesController extends Controller
{
    public $paymentService;
    public function __construct(PaymentInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    public function index()
    {
        return CourseResource::collection(Course::with(['teacher', 'reviews'])->paginate(10));
    }
    public function course($id)
    {
        $course = Course::findOrFail($id);
        return response()->json(CourseWithLessonsResource::make($course->load(['teacher', 'lessons'])), 200);
    }
    public function enroll($courseId)
    {
        $course = Course::findOrFail($courseId);
        $enrollment = Enrollment::where('course_id', $courseId)->where('user_id', Auth::user()->id)->first();
        if ($enrollment) {
            if ($enrollment->courseType == 'paid' && !$enrollment->paid) {
                return response()->json(['url' => $this->paymentService->pay($enrollment->uuid)], 200);
            }
            return response()->json([
                'message' => 'Student already enrolled in this course'
            ], 401);
        }
        if ($course->price == 0) {
            // free
            // enroll
            $enrollment = Enrollment::create([
                'user_id' => Auth::user()->id,
                'course_id' => $courseId,
                'courseType' => 'free',
                'paid' => true,
            ]);
            return response()->json([
                'message' => 'Enrollement done successfully'
            ], 200);
        } else {
            //  paid
            $enrollment = Enrollment::create([
                'user_id' => Auth::user()->id,
                'course_id' => $courseId,
                'courseType' => 'paid',
            ]);
            return response()->json(['url' => $this->paymentService->pay($enrollment->uuid)], 200);
        }
    }
    public function myCourses()
    {
        $enrolls = DB::table('enrollments')->where('user_id', Auth::user()->id)->where('paid', true)->pluck('course_id')->all();
        $course = Course::find($enrolls);
        return CourseResource::collection($course);
    }
    public function myCourse($id)
    {
        $course = Course::findOrFail($id);
        Gate::authorize('isEnrolled', $course);
        return FreeLessonsResource::collection($course->lessons);
    }

    public function lesson($id)
    {
        $lesson = Lesson::findOrFail($id)->load(['course', 'media']);

        Gate::authorize('isEnrolled', $lesson->course);

        $publicId = $lesson->media->public_id;
        $signedUrl = $this->getSignedVideoUrl($publicId);

        return redirect($signedUrl);
    }

    private function getSignedVideoUrl(string $publicId): string
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true],
        ]);

        return Media::fromParams($publicId, [
            'resource_type' => 'video',
            // 'format'        => 'mp4',
            'type'          => 'upload',
            'sign_url'      => true,
            'version'       => time(),
        ])->toUrl();
    }
}
