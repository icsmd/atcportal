<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ResolutionApplicationRequest.
 */
class ResolutionApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->application->canProvideResolution();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'resolution_file' => ['required', 'file', 'max:5000'],
            'temporary_documents' => ['nullable', 'array'],
        ];
    }
}
