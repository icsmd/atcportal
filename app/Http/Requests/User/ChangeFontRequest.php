<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class ChangeFontRequest.
 */
class ChangeFontRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'font_size' => ['required', Rule::in([User::FONT_SMALL, User::FONT_MEDIUM, User::FONT_LARGE, User::FONT_HUGE])],
        ];
    }
}
