<?php

namespace App\Http\Controllers;

use App\Http\Resources\EnrollementWithCourse;
use App\Http\Resources\EnrollemntResource;
use App\Http\Resources\ResultResource;
use App\Http\Resources\StudentDetailsResource;
use App\Http\Resources\StudentResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user =Auth::user();
       
         $search=$request->search ?? '';
        $user_courses =  Cache::remember('teacher_courses_.'.$user->id, 1800, function ()use ($user) {
            return $user->teacher->courses->pluck('id')->toArray();
        });
        $user_students= Cache::remember('teacher_students_.'.$user->id, 1800, function ()use ($user_courses) {
            return  DB::table('enrollments')->whereIn('course_id',$user_courses)->pluck('user_id')->toArray();
        });
       
        
        return Inertia('Students/Index', ['students' => StudentResource::collection(
            DB::table('users')
            ->whereIn('id',$user_students)
            ->where('users.name','like','%'.$search.'%')
            ->paginate(10)),
            'search'=>$search]);
    }
    public function student($id){
        $student=User::findOrFail($id);
         $user =Auth::user();

         $user_courses =  Cache::remember('teacher_courses_.'.$user->id, 1800, function ()use ($user) {
            return $user->teacher->courses->pluck('id')->toArray();
        });
        $user_students= Cache::remember('teacher_students_.'.$user->id, 1800, function ()use ($user_courses) {
            return  DB::table('enrollments')->whereIn('course_id',$user_courses)->pluck('user_id')->toArray();
        });
        
            if(!in_array($id,$user_students)){
                abort(403);
            }
            $exams_id=DB::table('exams')->whereIn('course_id',$user_courses)->pluck('id')->toArray();
            $results=$student->results->whereIn('exam_id',$exams_id);
            return Inertia('Students/show', [
                'student' => StudentDetailsResource::make($student),
                'enrollments'=>EnrollementWithCourse::collection($student->enrollments->whereIn('course_id',$user_courses)->load('course')),
                'results'=>ResultResource::collection($results),

            ]);
            
    }
}
