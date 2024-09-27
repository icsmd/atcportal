<?php

namespace Tests\Feature\Application;

use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class DeleteDraftApplicationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array
     */
    protected function applicationData()
    {
        return [
            'name' => 'police eryok',
            'rank' => 'elite',
            'badge_number' => '123',
            'unit' => 'unit',
            'office_address' => 'address',
            'tel' => '09101234567',
            'arrested_picture' => UploadedFile::fake()->image('mugshot.jpg'),
            'arrested_firstname' => 'firstname',
            'arrested_middlename' => 'middle',
            'arrested_lastname' => 'lastname',
            'arrested_suffix' => 'suffix',
            'arrested_address' => 'address',
            'arrested_tel' => '09101234567',
            'arrested_pob' => 'place of birth',
            'arrested_dob' => now()->subYears(10),
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
            'arrested_category' => Application::ELDERLY,
            'is_informed_of_right' => 'no',
            'mental_condition' => [
                'answers' => [
                    'question1' => 'yes',
                    'question2' => 'yes',
                    'question3' => 'yes',
                    'question4' => 'yes',
                ],
                'more_details' => [
                    'question1' => null,
                    'question2' => null,
                    'question3' => 'aaa',
                    'question4' => 'bbb',
                ],
            ],
            'physical_condition' => [
                'answers' => [
                    'question1' => 'yes',
                    'question2' => 'yes',
                    'question3' => 'yes',
                    'question4' => 'yes',
                    'question5' => 'yes',
                ],
                'more_details' => [
                    'question1' => 'aaa',
                    'question2' => null,
                    'question3' => 'bbb',
                    'question4' => 'ccc',
                    'question5' => 'ddd',
                ],
            ],
            'extension_reason' => ['A'],
            'reason_narration' => 'reason narration',
            'sworn_affidavit' => UploadedFile::fake()->image('test.jpg'),
            'logbook' => UploadedFile::fake()->image('test.jpg'),
            'book_sheet' => UploadedFile::fake()->image('test.jpg'),
            'spot_report' => UploadedFile::fake()->image('test.jpg'),
            'sworn_affidavit_witness' => UploadedFile::fake()->image('test.jpg'),
            'warrantless_arrest' => UploadedFile::fake()->image('test.jpg'),
            'terms' => true,
            'temporary_documents' => [],
        ];
    }

    /** @test */
    public function test_can_delete_draft()
    {
        $this->loginAsCustomUser([config('atc.access.permission.send_application'), config('atc.access.permission.update_application')]);

        $data = $this->applicationData();

        $this->post(route('applications.draft'), $data);

        $application = Application::all()->last();

        $arrested_picture = $application->getMediaFile('arrested_picture')->file_name;
        $sworn_affidavit = $application->getMediaFile('sworn_affidavit')->file_name;
        $logbook = $application->getMediaFile('logbook')->file_name;
        $book_sheet = $application->getMediaFile('book_sheet')->file_name;
        $spot_report = $application->getMediaFile('spot_report')->file_name;
        $sworn_affidavit_witness = $application->getMediaFile('sworn_affidavit_witness')->file_name;
        $warrantless_arrest = $application->getMediaFile('warrantless_arrest')->file_name;

        $this->delete(route('applications.delete.draft', ['application' => $application]));

        $this->assertDatabaseMissing('applications', [
            'id' => $application->id,
        ]);

        Storage::disk('local')->assertMissing($application->control_number.'/arrested_picture/'.$arrested_picture);
        Storage::disk('local')->assertMissing($application->control_number.'/sworn_affidavit/'.$sworn_affidavit);
        Storage::disk('local')->assertMissing($application->control_number.'/logbook/'.$logbook);
        Storage::disk('local')->assertMissing($application->control_number.'/book_sheet/'.$book_sheet);
        Storage::disk('local')->assertMissing($application->control_number.'/spot_report/'.$spot_report);
        Storage::disk('local')->assertMissing($application->control_number.'/sworn_affidavit_witness/'.$sworn_affidavit_witness);
        Storage::disk('local')->assertMissing($application->control_number.'/warrantless_arrest/'.$warrantless_arrest);
    }

    /** @test */
    public function test_can_delete_draft_application_with_support_documents()
    {
        $this->loginAsCustomUser([config('atc.access.permission.send_application'), config('atc.access.permission.update_application')]);

        $data = $this->applicationData();

        $uuid1 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $uuid2 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $data['temporary_documents'] = [$uuid1, $uuid2];

        $this->post(route('applications.draft'), $data);

        $application = Application::all()->last();

        $filenames = $application->getFiles('draft')->where('name', 'test')->pluck('file_name')->toArray();

        $this->delete(route('applications.delete.draft', ['application' => $application]));

        Storage::disk('local')->assertMissing('drafts/'.auth()->user()->id.'/'.$application->id.'/test/'.$filenames[0]);
        Storage::disk('local')->assertMissing('drafts/'.auth()->user()->id.'/'.$application->id.'/test/'.$filenames[1]);
    }

    /** @test */
    public function test_cant_delete_other_users_draft_application()
    {
        $this->loginAsCustomUser([config('atc.access.permission.send_application'), config('atc.access.permission.update_application')]);

        $application = Application::factory()->create([
            'user_id' => 100,
            'control_number' => 'ATC-001',
            'status' => Application::DRAFT,
            'reason_narration' => 'somewhat error',
        ]);

        $data = $this->applicationData();

        $response = $this->delete(route('applications.delete.draft', ['application' => $application]), $data);

        $response->assertStatus(404);
    }
}
