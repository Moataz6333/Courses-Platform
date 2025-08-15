<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegiterationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>['required','min:2','max:255'],
            'email'=>['required','min:2','max:255','email','unique:users'],
            'password'=>['required','min:6','max:255','confirmed'],
            'birthdate'=>['required','date','before:now'],
            'gender'=>['required',Rule::in(['male','female'])],
            'phone'=>['required','min:2','max:255'],
        ];
    }
}
