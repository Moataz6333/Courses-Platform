<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TeachersResourse;
use App\Http\Resources\Api\TeacherWithCoursesResourse;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()  {
        
        return TeachersResourse::collection(Teacher::with('user')->withCount('courses')->paginate(10));
    }
    public function teacher($id) {
        $teacher=Teacher::findOrFail($id)->load(['user','courses']);
        return response()->json(['teacher'=>TeacherWithCoursesResourse::make($teacher)], 200);
    }
}   
