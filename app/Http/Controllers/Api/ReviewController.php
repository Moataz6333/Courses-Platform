<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReviewRequest;
use App\Http\Requests\Api\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    

    
    
    public function store(StoreReviewRequest $request,$courseId)
    {
        $review = Review::where('course_id', $courseId)->where('user_id', Auth::user()->id)->first();
            if ($review) {
                return response()->json([
                    'message'=>'user reviewed before'
                ], 403);
            }
        $review=Review::create([
            'user_id'=>Auth::user()->id,
            'course_id'=>$courseId,
            'votes'=>$request->votes,
            'content'=>$request->content,
        ]);
        return response()->json([
            'message'=>'review created successfully!'
        ], 201);
    }

    public function update(UpdateReviewRequest $request,$reviewId)
    {
        $review=Review::findOrFail($reviewId);
        $review->update(
            ['votes'=>(int) $request->votes,
            'content'=>$request->content,]
        );
        return response()->json(['message'=>'review updated successfully'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
         $review=Review::findOrFail($id);
         Gate::authorize('OwnReview',$review);
         $review->delete();
        return response()->json(['message'=>'review deleted successfully'], 200);
    }
}
