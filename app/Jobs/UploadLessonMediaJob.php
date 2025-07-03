<?php

namespace App\Jobs;

use App\Interfaces\MediaInterface;
use App\Models\Media;
use App\Service\CloudinaryMediaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UploadLessonMediaJob implements ShouldQueue
{
    use Queueable;
    protected $filePath, $mediaType, $lessonId, $courseId ;
    public function __construct($filePath, $mediaType, $lessonId, $courseId)
    {
        $this->filePath = $filePath;
        $this->mediaType = $mediaType;
        $this->lessonId = $lessonId;
        $this->courseId = $courseId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $mediaService=new CloudinaryMediaService();
        try {
            $upload = $mediaService->upload($this->filePath, $this->courseId);
        
            Media::create([
                'lesson_id' => $this->lessonId,
                'path' => $upload['secure_url'],
                'public_id' => $upload['public_id'],
                'type' => $this->mediaType,
                'resource_type' => $upload['resource_type'],
            ]);
               return redirect()->route('lessons.index', $this->courseId)->with('success', 'Lesson created successfully!')->with('info', ' Media is being uploaded.');
        } catch (\Exception $e) {
            Log::error("Media upload failed for lesson {$this->lessonId}: " . $e->getMessage());
        }
    }
}
