<?php

use App\Models\Exam;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Gate;

Broadcast::channel('exam.{exam_id}', function ($user, $exam_id) {
    $exam=Exam::findOrFail($exam_id)->load('course');
    return Gate::allows('CourseOwner',$exam->course);
});
