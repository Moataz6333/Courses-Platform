<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Interfaces\MediaInterface;
use App\Models\Option;
use App\Models\Question;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class QuestionController extends Controller
{

    public $mediaService;
    public function __construct(MediaInterface $mediaService)
    {
        $this->mediaService = $mediaService;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = Question::findOrFail($id);
        Gate::authorize('CourseOwner', $question->exam->course);
        return Inertia::render('Exams/question', [
            'question' => QuestionResource::make($question)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionRequest $request, $questionId)
    {
        // dd($request->validated());
        $question = Question::findOrFail($questionId);
        if ($request->hasOptions) {
            $question->update([
                'head' => $request->questionHead ?? ' ',
                'points' => (int) $request->points,
                'correct_ans' => $request->options['isCorrect' === true]['answer'],
                'has_options' => $request->hasOptions,
            ]);
            if (count($question->options) != 0) {
                $question->options()->delete();
            }
            foreach ($request->options as $option) {
                Option::create([
                    'question_id' => $question->id,
                    'answer' => $option['answer'],
                    'isCorrect' => $option['isCorrect'],
                ]);
            }
        } else {
            if (count($question->options) != 0) {
                $question->options()->delete();
            }
            $question->update([
                'head' => $request->questionHead,
                'points' => (int)  $request->points,
                'correct_ans' => $request->answer,
                'has_options' => $request->hasOptions,
            ]);
        }

        // if has image
        // media
        if ($request->hasFile('media')) {

            try {
                if($question->media){
                    // delete
                    $this->mediaService->destroy($question->media);
                    }
                // upload
                $uploaded = $this->mediaService->upload($request->file('media')->getRealPath(), $question->exam->course_id);

                Media::create([
                    'question_id' => $question->id,
                    'public_id' => $uploaded['public_id'],
                    'resource_type' => $uploaded['resource_type'],
                    'path' => $uploaded['secure_url'],
                    'type' => 'image',
                ]);
            } catch (\Exception $ex) {
                return   redirect()->back()->with('error', $ex->getMessage());
            }
        }
        return redirect()->route('questions.edit', $question->id)->with('success', 'question updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question = Question::findOrFail($id);
        $examId = $question->exam_id;
        Gate::authorize('CourseOwner', $question->exam->course);
        $question->delete();
        return redirect()->route('exams.show', $examId)->with('success', 'question deleted successfully');
    }
   
}
