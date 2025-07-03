<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('isTeacher');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=>['required','min:2','max:255'],
            'description'=>['required','min:2'],
            'keyWords'=>['required','min:2','max:300'],
            'price'=>['required'],
            'track_id'=>['required','exists:tracks,id'],
            'thumbnail'=>['required','file'],
        ];
    }
}
