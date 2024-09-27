<?php

namespace App\Http\Requests\Application;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class DraftApplicationRequest.
 */
class DraftApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('send application');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'arrested_picture' => ['nullable', 'file', 'image', 'max:5000'],
            'arrested_dob' => ['nullable', 'date', 'before:now'],
            'arrested_sex' => ['nullable', Rule::in([Application::MALE, Application::FEMALE])],
            'arrested_status' => ['nullable', Rule::in([Application::SINGLE, Application::MARRIED, Application::WIDOWED, Application::SEPARATED])],
            'arrested_category' => ['nullable', Rule::in([Application::ELDERLY, Application::PWD, Application::PREGNANT_WOMAN, Application::WOMAN, Application::CHILDREN])],
            'arrested_age' => ['nullable', 'integer'],
            'arrested_weight' => ['nullable', 'numeric'],
            'arrested_height' => ['nullable'],
            'when' => ['nullable', 'date', 'before:now', 'after:'.now()->subDays(config('atc.access.days_of_detention'))->format('m/d/Y')],
            'sworn_affidavit' => ['nullable', 'file', 'max:5000'],
            'logbook' => ['nullable', 'file', 'max:5000'],
            'book_sheet' => ['nullable', 'file', 'max:5000'],
            'spot_report' => ['nullable', 'file', 'max:5000'],
            'sworn_affidavit_witness' => ['nullable', 'file', 'max:5000'],
            'warrantless_arrest' => ['nullable', 'file', 'max:5000'],
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
            'arrested_dob.before' => 'The date of birth must be a date before now.',
            'arrested_picture.file' => 'The mugshot field must be a file.',
            'arrested_picture.image' => 'The mugshot field must be an image.',
            'arrested_age.integer' => 'The age field must be a number',
            'arrested_picture.max' => 'The mugshot field must not be greater than 5000 kilobytes.',
            'arrested_weight.numeric' => 'The weight field must be a number.',
            'sworn_affidavit.max' => 'The sworn affidavit of arresting officer field must not be greater than 5000 kilobytes.',
            'sworn_affidavit.file' => 'The sworn affidavit of arresting officer field must be a file.',
            'logbook.max' => 'The log book field must not be greater than 5000 kilobytes.',
            'logbook.file' => 'The log book field must be a file.',
            'book_sheet.max' => 'The booking sheet field must not be greater than 5000 kilobytes.',
            'book_sheet.file' => 'The booking sheet field must be a file.',
            'spot_report.max' => 'The spot report field must not be greater than 5000 kilobytes.',
            'spot_report.file' => 'The spot report field must be a file.',
            'sworn_affidavit_witness.max' => 'The sworn affidavit of witness field must not be greater than 5000 kilobytes.',
            'sworn_affidavit_witness.file' => 'The sworn affidavit of witness field must be a file.',
            'warrantless_arrest.max' => 'The warrantless arrest field must not be greater than 5000 kilobytes.',
            'warrantless_arrest.file' => 'The warrantless arrest field must be a file.',
        ];
    }
}
