<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialMediaRequest;
use App\Http\Resources\SocialMediaResource;
use App\Models\SoicalMedia;
use App\Service\TeacherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SoicalMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $teacherService;
        public function __construct() {
            $this->teacherService =new TeacherService(Auth::user()->teacher);
            
        }
    public function index()
    {
        
        return Inertia::render('SocialMedia/index',[
            'socialmedia'=>$this->teacherService->socialMedia()
        ]);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(SocialMediaRequest $request)
    {   
        
       SoicalMedia::updateOrCreate([
            'teacher_id'=>Auth::user()->teacher->id,
            'name'=>$request->name
        ],
        [
            'link'=> $request->link
        ]);
        return redirect()->route('socialmedia.index')->with('success','social media updated successfully');
    }

    
}
