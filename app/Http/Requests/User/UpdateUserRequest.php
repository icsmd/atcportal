<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateUserRequest.
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->id != $this->user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'tel' => ['required', 'regex:/^\+639\d{9}$/'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id)],
            'password' => ['nullable', 'min:8'],
            'active' => ['required', 'boolean'],
            'permissions' => ['required', 'array'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'tel.regex' => 'Tel field must be in this format +639XXXXXXXXX.',
            'permissions.required' => 'You need to select permission at least 1.',
        ];
    }
}
