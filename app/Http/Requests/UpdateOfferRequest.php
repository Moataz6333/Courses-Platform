<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('CourseOwner',Course::findOrFail($this->course_id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id'=>['required','exists:courses,id'],
            'offer_id'=>['required','exists:offers,id'],
            'value'=>['required','integer','min:2','max:100'],
            'end_at'=>['nullable','date'],
            'isPublic'=>['required','boolean'],
            'code'=>['required_if:isPublic,false','nullable','min:4','max:8']
        ];
    }
}
