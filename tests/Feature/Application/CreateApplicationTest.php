<?php

namespace Tests\Feature\Application;

use App\Events\Application\ApplicationSent;
use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Storage;
use Tests\TestCase;

class CreateApplicationTest extends TestCase
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
    public function test_cant_send_application_when_no_send_application_permission()
    {
        $applicant = $this->loginAsCustomUser('no permission');

        $response = $this->post(route('applications.store'), $this->applicationData());

        $response->assertStatus(403);
    }

    /** @test */
    public function test_can_submit_application()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        $data = $this->applicationData();

        $response = $this->post(route('applications.store'), $data);

        $this->assertDatabaseHas('applications', [
            'name' => $data['name'],
            'rank' => $data['rank'],
            'badge_number' => $data['badge_number'],
            'unit' => $data['unit'],
            'office_address' => $data['office_address'],
            'tel' => $data['tel'],
            'arrested_firstname' => $data['arrested_firstname'],
            'arrested_middlename' => $data['arrested_middlename'],
            'arrested_lastname' => $data['arrested_lastname'],
            'arrested_suffix' => $data['arrested_suffix'],
            'arrested_address' => $data['arrested_address'],
            'arrested_tel' => $data['arrested_tel'],
            'arrested_pob' => $data['arrested_pob'],
            'arrested_dob' => $data['arrested_dob'],
            'arrested_sex' => $data['arrested_sex'],
            'arrested_status' => $data['arrested_status'],
            'arrested_spouse_name' => serialize($data['arrested_spouse_name']),
            'arrested_spouse_address' => serialize($data['arrested_spouse_address']),
            'arrested_weight' => $data['arrested_weight'],
            'arrested_height' => $data['arrested_height'],
            'arrested_eyes' => $data['arrested_eyes'],
            'arrested_hair' => $data['arrested_hair'],
            'arrested_complexion' => $data['arrested_complexion'],
            'arrested_occupation' => $data['arrested_occupation'],
            'arrested_nationality' => $data['arrested_nationality'],
            'arrested_tribe' => $data['arrested_tribe'],
            'arrested_language' => $data['arrested_language'],
            'arrested_educ_attainment' => $data['arrested_educ_attainment'],
            'arrested_school_name' => $data['arrested_school_name'],
            'arrested_school_address' => $data['arrested_school_address'],
            'arrested_marks' => serialize($data['arrested_marks']),
            'arrested_location_marks' => $data['arrested_location_marks'],
            'arrested_defect' => $data['arrested_defect'],
            'who' => $data['who'],
            'when' => $data['when'].':00',
            'where' => $data['where'],
            'what' => $data['what'],
            'how' => $data['how'],
            'other_details' => $data['other_details'],
            'arrested_category' => $data['arrested_category'],
            'is_informed_of_right' => $data['is_informed_of_right'],
            'mental_condition' => serialize($data['mental_condition']),
            'physical_condition' => serialize($data['physical_condition']),
            'extension_reason' => serialize($data['extension_reason']),
            'reason_narration' => $data['reason_narration'],
            'status' => Application::AVAILABLE,
        ]);

        $application = Application::all()->last();

        $arrested_picture = $application->getMediaFile('arrested_picture')->file_name;
        $sworn_affidavit = $application->getMediaFile('sworn_affidavit')->file_name;
        $logbook = $application->getMediaFile('logbook')->file_name;
        $book_sheet = $application->getMediaFile('book_sheet')->file_name;
        $spot_report = $application->getMediaFile('spot_report')->file_name;
        $sworn_affidavit_witness = $application->getMediaFile('sworn_affidavit_witness')->file_name;
        $warrantless_arrest = $application->getMediaFile('warrantless_arrest')->file_name;

        Storage::disk('local')->assertExists($application->control_number.'/arrested_picture/'.$arrested_picture);
        Storage::disk('local')->assertExists($application->control_number.'/sworn_affidavit/'.$sworn_affidavit);
        Storage::disk('local')->assertExists($application->control_number.'/logbook/'.$logbook);
        Storage::disk('local')->assertExists($application->control_number.'/book_sheet/'.$book_sheet);
        Storage::disk('local')->assertExists($application->control_number.'/spot_report/'.$spot_report);
        Storage::disk('local')->assertExists($application->control_number.'/sworn_affidavit_witness/'.$sworn_affidavit_witness);
        Storage::disk('local')->assertExists($application->control_number.'/warrantless_arrest/'.$warrantless_arrest);
    }

    /** @test */
    public function test_can_submit_application_with_support_documents()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        $data = $this->applicationData();

        $uuid1 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $uuid2 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $data['temporary_documents'] = [$uuid1, $uuid2];

        $response = $this->post(route('applications.store'), $data);

        $application = Application::all()->last();

        $filenames = $application->getFiles('supporting_documents')->pluck('file_name')->toArray();

        Storage::disk('local')->assertExists($application->control_number.'/supporting_documents/'.$filenames[0]);
        Storage::disk('local')->assertExists($application->control_number.'/supporting_documents/'.$filenames[1]);
    }

    /** @test */
    public function test_check_control_number_sequence()
    {
        Carbon::setTestNow(Carbon::create(2001, 5, 21, 11, 11));

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        $response = $this->post(route('applications.store'), $this->applicationData());

        $application = $applicant->applications[0];

        $this->assertEquals('ATC01FD210520016F', $application->control_number);
    }

    /** @test */
    public function test_cant_create_draft_application_when_no_send_application_permission()
    {
        $applicant = $this->loginAsCustomUser('no permission');

        $response = $this->post(route('applications.draft'), $this->applicationData());

        $response->assertStatus(403);
    }

    /** @test */
    public function test_can_create_draft_application()
    {
        $applicant = $this->loginAsCustomUser([config('atc.access.permission.send_application')]);

        $data = $this->applicationData();

        $response = $this->post(route('applications.draft'), $data);

        $this->assertDatabaseHas('applications', [
            'name' => $data['name'],
            'rank' => $data['rank'],
            'badge_number' => $data['badge_number'],
            'unit' => $data['unit'],
            'office_address' => $data['office_address'],
            'tel' => $data['tel'],
            'arrested_firstname' => $data['arrested_firstname'],
            'arrested_middlename' => $data['arrested_middlename'],
            'arrested_lastname' => $data['arrested_lastname'],
            'arrested_suffix' => $data['arrested_suffix'],
            'arrested_address' => $data['arrested_address'],
            'arrested_tel' => $data['arrested_tel'],
            'arrested_pob' => $data['arrested_pob'],
            'arrested_dob' => $data['arrested_dob'],
            'arrested_sex' => $data['arrested_sex'],
            'arrested_status' => $data['arrested_status'],
            'arrested_spouse_name' => serialize($data['arrested_spouse_name']),
            'arrested_spouse_address' => serialize($data['arrested_spouse_address']),
            'arrested_weight' => $data['arrested_weight'],
            'arrested_height' => $data['arrested_height'],
            'arrested_eyes' => $data['arrested_eyes'],
            'arrested_hair' => $data['arrested_hair'],
            'arrested_complexion' => $data['arrested_complexion'],
            'arrested_occupation' => $data['arrested_occupation'],
            'arrested_nationality' => $data['arrested_nationality'],
            'arrested_tribe' => $data['arrested_tribe'],
            'arrested_language' => $data['arrested_language'],
            'arrested_educ_attainment' => $data['arrested_educ_attainment'],
            'arrested_school_name' => $data['arrested_school_name'],
            'arrested_school_address' => $data['arrested_school_address'],
            'arrested_marks' => serialize($data['arrested_marks']),
            'arrested_location_marks' => $data['arrested_location_marks'],
            'arrested_defect' => $data['arrested_defect'],
            'who' => $data['who'],
            'when' => $data['when'].':00',
            'where' => $data['where'],
            'what' => $data['what'],
            'how' => $data['how'],
            'other_details' => $data['other_details'],
            'arrested_category' => $data['arrested_category'],
            'is_informed_of_right' => $data['is_informed_of_right'],
            'mental_condition' => serialize($data['mental_condition']),
            'physical_condition' => serialize($data['physical_condition']),
            'extension_reason' => serialize($data['extension_reason']),
            'reason_narration' => $data['reason_narration'],
            'status' => Application::DRAFT,
        ]);

        $application = Application::all()->last();

        $arrested_picture = $application->getFiles('draft')->where('name', 'arrested_picture')->first()->file_name;
        $sworn_affidavit = $application->getFiles('draft')->where('name', 'sworn_affidavit')->first()->file_name;
        $logbook = $application->getFiles('draft')->where('name', 'logbook')->first()->file_name;
        $book_sheet = $application->getFiles('draft')->where('name', 'book_sheet')->first()->file_name;
        $spot_report = $application->getFiles('draft')->where('name', 'spot_report')->first()->file_name;
        $sworn_affidavit_witness = $application->getFiles('draft')->where('name', 'sworn_affidavit_witness')->first()->file_name;
        $warrantless_arrest = $application->getFiles('draft')->where('name', 'warrantless_arrest')->first()->file_name;

        Storage::disk('local')->assertExists('drafts/'.auth()->user()->id.'/'.$application->id.'/arrested_picture/'.$arrested_picture);
        Storage::disk('local')->assertExists('drafts/'.auth()->user()->id.'/'.$application->id.'/sworn_affidavit/'.$sworn_affidavit);
        Storage::disk('local')->assertExists('drafts/'.auth()->user()->id.'/'.$application->id.'/logbook/'.$logbook);
        Storage::disk('local')->assertExists('drafts/'.auth()->user()->id.'/'.$application->id.'/book_sheet/'.$book_sheet);
        Storage::disk('local')->assertExists('drafts/'.auth()->user()->id.'/'.$application->id.'/spot_report/'.$spot_report);
        Storage::disk('local')->assertExists('drafts/'.auth()->user()->id.'/'.$application->id.'/sworn_affidavit_witness/'.$sworn_affidavit_witness);
        Storage::disk('local')->assertExists('drafts/'.auth()->user()->id.'/'.$application->id.'/warrantless_arrest/'.$warrantless_arrest);
    }

    /** @test */
    public function test_can_create_draft_with_supported_documents()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        $data = $this->applicationData();

        $uuid1 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $uuid2 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $data['temporary_documents'] = [$uuid1, $uuid2];

        $response = $this->post(route('applications.draft'), $data);

        $application = Application::all()->last();

        $filenames = $application->getFiles('draft')->where('name', 'test')->pluck('file_name')->toArray();

        Storage::disk('local')->assertExists('drafts/'.auth()->user()->id.'/'.$application->id.'/test/'.$filenames[0]);
        Storage::disk('local')->assertExists('drafts/'.auth()->user()->id.'/'.$application->id.'/test/'.$filenames[1]);
    }

    /** @test */
    public function test_dont_fire_notification_when_saving_draft()
    {
        Bus::fake();

        $applicant = $this->loginAsCustomUser([config('atc.access.permission.send_application'), config('atc.access.permission.update_application')]);

        $response = $this->post(route('applications.draft'), [
            'sworn_affidavit' => UploadedFile::fake()->image('test.jpg'),
            'spot_report' => UploadedFile::fake()->image('test.jpg'),
            'sworn_affidavit_witness' => UploadedFile::fake()->image('test.jpg'),
        ]);

        Bus::assertNotDispatched(\App\Jobs\SendNotificationOnUserForNewApplication::class);
    }

    /** @test */
    public function test_fire_event_after_sending_application()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        Event::fake();

        $response = $this->post(route('applications.store'), $this->applicationData());

        Event::assertDispatched(ApplicationSent::class);
    }
}
