<?php

namespace App\Http\Requests;

use App\Models\Exam;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $exam=Exam::findOrFail($this->examId)->load('course');
        return Gate::allows('CourseOwner',$exam->course);
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
            'answer'=>['nullable','min:2'],
            'points'=>['required','integer','min:0'],
            'hasOptions'=>['required','boolean'],
            'options'=>['nullable','array'],

        ];
    }
}
