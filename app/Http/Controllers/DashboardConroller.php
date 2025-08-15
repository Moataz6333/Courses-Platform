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
        $user_courses = $user->teacher->courses->pluck('id')->toArray();
        // male & female
        $students_gender = DB::table('users')
            ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
            ->whereIn('enrollments.course_id', $user_courses)
            ->select('users.gender', DB::raw('COUNT(users.gender) as count'))
            ->groupBy('gender')
            ->get();
        // enrollments date
        $enrollemnts_dates = DB::table('enrollments')
            ->whereIn('course_id', $user_courses)
            ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count'))
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_students' => DB::table('enrollments')->whereIn('course_id', function ($query) use ($user) {
                    $query->select('id')->from('courses')->where('teacher_id', $user->id);
                })->count(),
                'total_courses' => DB::table('courses')->where('teacher_id', $user->id)->count(),
                'average_course_vote' => round(DB::table('reviews')->join('courses', 'reviews.course_id', '=', 'courses.id')->where('courses.teacher_id', $user->id)->average('reviews.votes'), 1),
            ],
            'students_gender' => $students_gender,
            'course_enrollments' => $course_enrollments,
            'enrollemnts_dates' => $enrollemnts_dates,
        ]);
    }
}
