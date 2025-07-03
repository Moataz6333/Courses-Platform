<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\EnrollemntResource;
use App\Http\Resources\LessonsResource;
use App\Http\Resources\TrackResource;
use App\Interfaces\MediaInterface;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Media;
use App\Models\Teacher;
use App\Models\Thumbnail;
use App\Models\Track;
use App\Service\TeacherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class CourseController extends Controller
{
    public $teacherService;
     public $mediaService;
    public function __construct(MediaInterface $mediaService)
    {
        $this->teacherService = new TeacherService(Auth::user()->teacher);
        $this->mediaService = $mediaService;
    }
    public function index()
    {
        return Inertia::render('Courses/index', [
            'courses' => $this->teacherService->courses()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Courses/create',[
            'tracks'=>TrackResource::collection(Track::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $validated = $request->validated();
        $course=  Course::create([
            "title" => $validated['title'],
            "description" => $validated['description'],
            "keyWords" => $validated['keyWords'],
            "price" => (float) $validated['price'],
            "teacher_id" => Auth::user()->teacher->id,
            "track_id" => $validated['track_id'],
        ]);
          if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                if ($this->mediaService->destroy($course->thumbnail)) {
                    $uploaded = $this->mediaService->upload($request->file('thumbnail')->getRealPath(), $course->id);
                if ($uploaded) {
                    $course->thumbnail()->update([
                        'public_id' => $uploaded['public_id'],
                        'resource_type' => $uploaded['resource_type'],
                        'path' => $uploaded['secure_url'],
                    ]);
                } else {
                    return redirect()->route('courses.create')->with('error', 'Something went wrong');
                }
                } else {
                     return redirect()->route('courses.create')->with('error', 'Something went wrong');
                }
                
            } else {
                $uploaded = $this->mediaService->upload($request->file('thumbnail')->getRealPath(), $course->id);
                if ($uploaded) {
                    Thumbnail::create([
                        'public_id' => $uploaded['public_id'],
                        'resource_type' => $uploaded['resource_type'],
                        'path' => $uploaded['secure_url'],
                        'course_id' => $course->id,
                    ]);
                } else {
                    return redirect()->route('courses.create')->with('error', 'Something went wrong');
                }
            }
        }
        return redirect()->route('courses.create')->with('success', 'Course Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::findOrFail($id);
        Gate::authorize('CourseOwner', $course);
        return Inertia::render('Courses/show', [
            'course' => CourseResource::make($course),
            'lessons'=>LessonsResource::collection($course->lessons->load(['media','thumbnail']))
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $course = Course::findOrFail($id);
        Gate::authorize('CourseOwner', $course);
        return Inertia::render('Courses/edit', [
            'course' => CourseResource::make($course),
            'tracks'=>TrackResource::collection(Track::all())
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update([
            'title' => $request->title,
            'description' => $request->description,
            'keyWords' => $request->keyWords,
            'price' => $request->price,
            'track_id' => $request->track_id,
        ]);
          if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                if ($this->mediaService->destroy($course->thumbnail)) {
                    $uploaded = $this->mediaService->upload($request->file('thumbnail')->getRealPath(), $course->id);
                if ($uploaded) {
                    $course->thumbnail()->update([
                        'public_id' => $uploaded['public_id'],
                        'resource_type' => $uploaded['resource_type'],
                        'path' => $uploaded['secure_url'],
                    ]);
                } else {
                    return redirect()->route('courses.edit', $course->id)->with('error', 'Something went wrong');
                }
                } else {
                     return redirect()->route('courses.edit', $course->id)->with('error', 'Something went wrong');
                }
                
            } else {
                $uploaded = $this->mediaService->upload($request->file('thumbnail')->getRealPath(), $course->id);
                if ($uploaded) {
                    Thumbnail::create([
                        'public_id' => $uploaded['public_id'],
                        'resource_type' => $uploaded['resource_type'],
                        'path' => $uploaded['secure_url'],
                        'course_id' => $course->id,
                    ]);
                } else {
                    return redirect()->route('courses.edit', $course->id)->with('error', 'Something went wrong');
                }
            }
        }
        return redirect()->route('courses.edit', $course->id)->with('success', 'Course Updated Successfully');
    }

    
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        Gate::authorize('CourseOwner', $course);
         $this->mediaService->destroyFolder($course);
            $course->delete();
            return redirect()->route('courses.index')->with('success', 'Course Deleted Successfully');
       
    }
    // enrollments
    public function enrollments($courseId)  {
        $course=Course::findOrFail($courseId);
        Gate::authorize('CourseOwner',$course);
        // dd(EnrollemntResource::collection(Enrollment::where('course_id',$courseId)->paginate(10)));
        return Inertia::render('Courses/enrollments',[
            'enrollments'=>EnrollemntResource::collection(Enrollment::where('course_id',$courseId)->orderByDesc('created_at')->with('transaction')->paginate(10)),
            'title'=>$course->title,
            'courseType'=>$course->price==0 ? 'free' : 'paid',
            'courseId'=>$courseId,
        ]);
    }
    
}
