<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $lesson=Lesson::findOrFail($this->lessonId);
        return Gate::allows('CourseOwner',$lesson->course);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'media'=>['required','min:2','max:300'],
            'mediaType'=>['required',Rule::in(['image','video','pdf'])],
            'public_id'=>['required','min:2','max:255'],
            'resourceType'=>['required','min:2','max:255'],
        ];
    }
}
