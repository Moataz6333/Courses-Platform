<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
         $search=$request->search ?? '';
        $user_courses = $user->teacher->courses->pluck('id')->toArray();
        
        return Inertia('Students/Index', ['students' => StudentResource::collection(
            DB::table('users')
            ->whereIn('id',DB::table('enrollments')->whereIn('course_id',$user_courses)->pluck('user_id')->toArray())
            ->where('users.name','like','%'.$search.'%')
            ->paginate(10)),
            'search'=>$search]);
    }
    public function student($id){
        $id;
    }
}
