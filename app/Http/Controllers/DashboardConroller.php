<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardConroller extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $course_enrollments = DB::table('courses')
            ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->where('courses.teacher_id', $user->id)
            ->select('courses.title as course', DB::raw('COUNT(enrollments.id) as students'))
            ->groupBy('courses.title')
            ->get();
        
        return Inertia::render('Dashboard', [
            'stats' => [
                'total_students' => DB::table('enrollments')->whereIn('course_id', function ($query) use ($user) {
                    $query->select('id')->from('courses')->where('teacher_id', $user->id);
                })->count(),
                'total_courses' => DB::table('courses')->where('teacher_id', $user->id)->count(),
                'average_course_vote' => round(DB::table('reviews')->join('courses','reviews.course_id','=','courses.id')->where('courses.teacher_id',$user->id)->average('reviews.votes'),2),
            ],
            'grade_distribution' => [
                'A' => 12,
                'B' => 21,
                'C' => 9,
                'F' => 4,
            ],
            'course_enrollments' =>$course_enrollments,
        ]);
    }
}
