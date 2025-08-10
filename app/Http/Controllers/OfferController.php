<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\OfferResource;
use App\Models\Course;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($courseId)  {
        $course=Course::findOrFail($courseId)->load('offers');
        Gate::authorize('CourseOwner',$course);
        return Inertia::render('Courses/offers',[
            'course'=>CourseResource::make($course),
            'offers'=>OfferResource::collection($course->offers)
        ]);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferRequest $request)
    {
        
        $offer=new Offer();
        $offer->course_id=$request->course_id;
        $offer->value=$request->value;
        if($request->end_at){
            $offer->end_at=date_create($request->end_at);
        }
        $offer->isPublic=$request->isPublic;
        if(!$request->isPublic){
            $offer->code=$request->code;
        }
        $offer->save();
        return redirect()->route('course.offers',$offer->course_id)->with('success','Offer Created Successfully!');
     
        

    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferRequest $request, string $id)
    {
        $offer=Offer::findOrFail($id);
         $offer->value=$request->value;
        if($request->end_at){
            $offer->end_at=date_create($request->end_at);
        }
        $offer->isPublic=$request->isPublic;
        if(!$request->isPublic){
            $offer->code=$request->code;
        }else{
            if($offer->code){
                $offer->code='';
            }
        }
        $offer->save();
        return redirect()->route('course.offers',$offer->course_id)->with('success','Offer Updated Successfully!');
     
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $offer=Offer::findOrFail($id);
        Gate::authorize('CourseOwner',Course::find($offer->course_id));
        $offer->delete();
     return redirect()->route('course.offers',$offer->course_id)->with('success','Offer Deleted Successfully!');

    }
}
