<?php

namespace App\Http\Requests\Application;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateApplicationRequest.
 */
class UpdateApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->application->canUpdate();
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
            'rank' => ['required'],
            'badge_number' => ['required'],
            'unit' => ['required'],
            'office_address' => ['required'],
            'tel' => ['required'],
            'arrested_picture' => ['required_without_all:arrested_picture_path', 'nullable', 'file', 'image', 'max:5000'],
            'arrested_firstname' => ['required'],
            'arrested_lastname' => ['required'],
            'arrested_address' => ['required'],
            'arrested_tel' => ['required'],
            'arrested_pob' => ['required'],
            'arrested_dob' => ['nullable', 'date', 'before:now'],
            'arrested_age' => ['nullable', 'integer'],
            'arrested_category' => ['nullable', Rule::in([Application::ELDERLY, Application::PWD, Application::PREGNANT_WOMAN, Application::WOMAN, Application::CHILDREN])],
            'arrested_sex' => ['required', Rule::in([Application::MALE, Application::FEMALE])],
            'arrested_status' => ['required', Rule::in([Application::SINGLE, Application::MARRIED, Application::WIDOWED, Application::SEPARATED])],
            'arrested_weight' => ['required', 'numeric'],
            'arrested_height' => ['required'],
            'arrested_eyes' => ['required'],
            'arrested_hair' => ['required'],
            'arrested_complexion' => ['required'],
            'arrested_occupation' => ['required'],
            'arrested_nationality' => ['required'],
            'arrested_tribe' => ['required'],
            'arrested_language' => ['required'],
            'arrested_educ_attainment' => ['required'],
            'arrested_school_name' => ['required'],
            'arrested_school_address' => ['required'],
            'when' => ['required', 'date'],
            'where' => ['required'],
            'what' => ['required'],
            'how' => ['required'],
            'other_details' => ['required'],
            'extension_reason' => [$this->application->is_extension ? 'required' : 'nullable', 'array'],
            'reason_narration' => ['required'],
            'sworn_affidavit' => ['required_without_all:sworn_affidavit_path', 'nullable', 'file', 'max:5000'],
            'logbook' => ['required_without_all:logbook_path', 'nullable', 'file', 'max:5000'],
            'book_sheet' => ['required_without_all:book_sheet_path', 'nullable', 'file', 'max:5000'],
            'spot_report' => ['required_without_all:spot_report_path', 'nullable', 'file', 'max:5000'],
            'sworn_affidavit_witness' => ['nullable', 'file', 'max:5000'],
            'warrantless_arrest' => ['nullable', 'file', 'max:5000'],
            'temporary_documents' => ['sometimes', 'array'],
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
            'tel.required' => 'The contact no. field is required.',
            'arrested_picture.required_without_all' => 'The mugshot field is required.',
            'arrested_picture.file' => 'The mugshot field must be a file.',
            'arrested_picture.image' => 'The mugshot field must be an image.',
            'arrested_picture.max' => 'The mugshot field must not be greater than 5000 kilobytes.',
            'arrested_firstname.required' => 'The first name field is required.',
            'arrested_lastname.required' => 'The last name field is required.',
            'arrested_address.required' => 'The full address field is required.',
            'arrested_tel.required' => 'The tel no. field is required.',
            'arrested_pob.required' => 'The place of birth field is required.',
            'arrested_age.integer' => 'The age field must be a number',
            'arrested_dob.before' => 'The date of birth must be a date before now.',
            'arrested_sex.required' => 'The sex field is required.',
            'arrested_status.required' => 'The marital status field is required.',
            'arrested_weight.required' => 'The weight field is required.',
            'arrested_weight.numeric' => 'The weight field must be a number.',
            'arrested_height.required' => 'The height field is required.',
            'arrested_eyes.required' => 'The eyes field is required.',
            'arrested_hair.required' => 'The hair field is required.',
            'arrested_complexion.required' => 'The complexion field is required.',
            'arrested_occupation.required' => 'The occupation field is required.',
            'arrested_nationality.required' => 'The nationality field is required.',
            'arrested_tribe.required' => 'The tribe/group field is required.',
            'arrested_language.required' => 'The dialect/language field is required.',
            'arrested_educ_attainment.required' => 'The highest school attainment field is required.',
            'arrested_school_name.required' => 'The name of school field is required.',
            'arrested_school_address.required' => 'The location of school field is required.',
            'sworn_affidavit.required_without_all' => 'The sworn affidavit of arresting officer field is required.',
            'sworn_affidavit.max' => 'The sworn affidavit of arresting officer field must not be greater than 5000 kilobytes.',
            'sworn_affidavit.file' => 'The sworn affidavit of arresting officer field must be a file.',
            'logbook.required_without_all' => 'The log book field is required.',
            'logbook.max' => 'The log book field must not be greater than 5000 kilobytes.',
            'logbook.file' => 'The log book field must be a file.',
            'book_sheet.required_without_all' => 'The booking sheet field is required.',
            'book_sheet.max' => 'The booking sheet field must not be greater than 5000 kilobytes.',
            'book_sheet.file' => 'The booking sheet field must be a file.',
            'spot_report.required_without_all' => 'The spot report field is required.',
            'spot_report.max' => 'The spot report field must not be greater than 5000 kilobytes.',
            'spot_report.file' => 'The spot report field must be a file.',
            'sworn_affidavit_witness.max' => 'The sworn affidavit of witness field must not be greater than 5000 kilobytes.',
            'sworn_affidavit_witness.file' => 'The sworn affidavit of witness field must be a file.',
            'warrantless_arrest.max' => 'The warrantless arrest field must not be greater than 5000 kilobytes.',
            'warrantless_arrest.file' => 'The warrantless arrest field must be a file.',
        ];
    }
}
