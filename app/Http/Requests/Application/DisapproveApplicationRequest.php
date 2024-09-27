<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DisapproveApplicationRequest.
 */
class DisapproveApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->application->canDisapprove();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'remarks' => ['required_without:temporary_documents'],
            'temporary_documents' => ['required_without:remarks', 'array'],
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
            'remarks.required_without' => 'The remarks field is required when attachment is not present.',
            'temporary_documents.required_without' => 'The attachment field is required when remarks is not present.',
        ];
    }
}
