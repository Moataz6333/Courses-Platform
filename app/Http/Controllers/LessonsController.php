<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\LessonsResource;
use App\Interfaces\MediaInterface;
use App\Jobs\UploadLessonMediaJob;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Thumbnail;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class LessonsController extends Controller
{
    public $mediaService;
    public function __construct(MediaInterface $mediaService)
    {
        $this->mediaService = $mediaService;
    }
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        return Inertia::render('Lessons/index', [
            'course' => CourseResource::make($course),
            'lessons' => LessonsResource::collection($course->lessons->load('media'))
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request, $courseId)
    {
        $data = $request->validated();
        Lesson::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'course_id' => $courseId
        ]);

        return redirect()->route('lessons.index', $courseId)->with('success', 'Lesson created successfully!');
    }

    // media
    public function media($id)
    {
        $lesson = Lesson::findOrFail($id);
        return Inertia::render('Lessons/media', [
            'lesson' => LessonsResource::make($lesson)
        ]);
    }
    // store media
    public function storeMedia(StoreMediaRequest $request, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId)->load('media');
        if ($lesson->media) {
            if ($this->mediaService->destroy($lesson->media)) {
                $lesson->media()->update([
                    'type' => $request->mediaType,
                    'path' => $request->media,
                    'public_id' => $request->public_id,
                    'resource_type' => $request->resourceType
                ]);
            } else {
                return redirect()->route('lessons.media', $lesson->id)->with('error', 'Cant delete the current media!');
            }
        } else {
            Media::Create(
                [
                    'lesson_id' => $lesson->id,
                    'type' => $request->mediaType,
                    'path' => $request->media,
                    'public_id' => $request->public_id,
                    'resource_type' => $request->resourceType
                ]
            );
        }

        return redirect()->route('lessons.media', $lessonId)->with('success', 'media stored successfully!');
    }
    // deleteMedia
    public function deleteMedia($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId)->load('media');
        Gate::authorize('CourseOwner', $lesson->course);
        if ($this->mediaService->destroy($lesson->media)) {
            $lesson->media()->delete();
            return redirect()->route('lessons.media', $lesson->id)->with('success', 'Media deleted Successfully!');
        } else {
            return redirect()->route('lessons.media', $lesson->id)->with('error', 'Media not deleted!');
        }
    }

    public function edit(string $id)
    {
        $lesson = Lesson::findOrFail($id);
        Gate::authorize('CourseOwner', $lesson->course);
        return Inertia::render('Lessons/edit', [
            'lesson' => LessonsResource::make($lesson->load('media'))
        ]);
    }

    public function update(UpdateLessonRequest $request, $lessonId)
    {

        $lesson = Lesson::findOrFail($lessonId)->load('thumbnail');
        $data = $request->validated();
        $lesson->update([
            'title' => $data['title'],
            'description' => $data['description'],
        ]);
        if ($request->hasFile('thumbnail')) {
            if ($lesson->thumbnail) {
                if ($this->mediaService->destroy($lesson->thumbnail)) {
                    $uploaded = $this->mediaService->upload($request->file('thumbnail')->getRealPath(), $lesson->course_id);
                if ($uploaded) {
                    $lesson->thumbnail()->update([
                        'public_id' => $uploaded['public_id'],
                        'resource_type' => $uploaded['resource_type'],
                        'path' => $uploaded['secure_url'],
                    ]);
                } else {
                    return redirect()->route('lessons.edit', $lesson->id)->with('error', 'Something went wrong');
                }
                } else {
                     return redirect()->route('lessons.edit', $lesson->id)->with('error', 'Something went wrong');
                }
                

            } else {
                $uploaded = $this->mediaService->upload($request->file('thumbnail')->getRealPath(), $lesson->course_id);
                if ($uploaded) {
                    Thumbnail::create([
                        'public_id' => $uploaded['public_id'],
                        'resource_type' => $uploaded['resource_type'],
                        'path' => $uploaded['secure_url'],
                        'lesson_id' => $lesson->id,
                    ]);
                } else {
                    return redirect()->route('lessons.edit', $lesson->id)->with('error', 'Something went wrong');
                }
            }
        }
        return redirect()->route('lessons.edit', $lesson->id)->with('success', 'Lesson updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lesson = Lesson::findOrFail($id)->load(['media','thumbnail']);
        Gate::authorize('CourseOwner', $lesson->course);
        $courseId = $lesson->course_id;
        // delete the media and thumbnail if exists
        if($lesson->media){
            $this->mediaService->destroy($lesson->media);
        }
        if($lesson->thumbnail){
            $this->mediaService->destroy($lesson->thumbnail);
        }
        $lesson->delete();
        return redirect()->route('lessons.index', $courseId)->with('success', 'Lesson deleted successfully!');
    }
}
