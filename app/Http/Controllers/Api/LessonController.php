<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLessonRequest;
use App\Http\Resources\LessonsResource;
use App\Jobs\UploadLessonMediaJob;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class LessonController extends Controller
{
    public function lessons($courseId){
        $course=Course::findOrFail($courseId);
        Gate::authorize('CourseOwner',$course);
        return response()->json(LessonsResource::collection($course->lessons->load('media')), 200);
    }
    public function store(StoreLessonRequest $request, $courseId)
    {
        $data = $request->validated();

        try {

            DB::beginTransaction();
            $lesson = Lesson::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'course_id' => $courseId
            ]);
            // media
            // Queue the media upload job
            dispatch(new UploadLessonMediaJob(
                $request->file('media')->getRealPath(),
                $data['mediaType'],
                $lesson->id,
                $courseId
            ));
           

            DB::commit();
         
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('Lesson creation failed: ' . $ex->getMessage());

            return back()->with('error', 'Failed to create lesson. Please try again.');
        }
    }
}
