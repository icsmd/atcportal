<?php

namespace Database\Factories;

use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'control_number' => 'ATC-001',
            'name' => 'police eryok',
            'rank' => 'elite',
            'badge_number' => '123',
            'unit' => 'unit',
            'office_address' => 'address',
            'tel' => '09101234567',
            'arrested_firstname' => 'firstname',
            'arrested_middlename' => 'middle',
            'arrested_lastname' => 'lastname',
            'arrested_suffix' => 'suffix',
            'arrested_address' => 'address',
            'arrested_tel' => '09101234567',
            'arrested_pob' => 'place of birth',
            'arrested_dob' => new Carbon('2001-01-01'),
            'arrested_age' => 21,
            'arrested_sex' => Application::MALE,
            'arrested_status' => Application::SINGLE,
            'arrested_spouse_name' => ['spouse name'],
            'arrested_spouse_address' => ['spouse address'],
            'arrested_weight' => 50,
            'arrested_height' => 165,
            'arrested_eyes' => 'eyes',
            'arrested_hair' => 'hair',
            'arrested_complexion' => 'complexion',
            'arrested_occupation' => 'occupation',
            'arrested_nationality' => 'nationality',
            'arrested_tribe' => 'tribe',
            'arrested_language' => 'language',
            'arrested_educ_attainment' => 'educational attainment',
            'arrested_school_name' => 'school name',
            'arrested_school_address' => 'school address',
            'arrested_marks' => ['mole'],
            'arrested_location_marks' => 'location of marks',
            'arrested_defect' => 'defect',
            'who' => 'firstname lastname suffix',
            'when' => now()->startOfDay()->format('Y-m-d H:i'),
            'where' => 'where',
            'what' => 'what',
            'how' => 'how',
            'why' => 'why',
            'other_details' => 'other details',
            'extension_reason' => ['A'],
            'reason_narration' => 'reason narration',
            'status' => Application::AVAILABLE,
        ];
    }
}
