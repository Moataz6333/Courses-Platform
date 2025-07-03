<?php

namespace App\Http\Requests;

use App\Models\Question;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         
        $question=Question::findOrFail($this->questionId);

        return Gate::allows('CourseOwner',$question->exam->course);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'questionHead'=>['nullable','min:2','max:300'],
            'media'=>['nullable','image'],
            'answer'=>['nullable'],
            'points'=>['required','integer'],
            'hasOptions'=>['required','boolean'],
            'options'=>['nullable','array'],

        ];
    }
}
