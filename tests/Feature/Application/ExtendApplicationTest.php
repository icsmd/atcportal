<?php

namespace Tests\Feature\Application;

use App\Events\Application\ExtensionRequested;
use App\Models\Application;
use Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Storage;
use Tests\TestCase;

class ExtendApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_cant_extend_application_when_no_send_application_permission()
    {
        $applicant = $this->loginAsCustomUser('no permission');

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.extension', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_cant_extend_application_when_status_is_not_approved_and_dont_have_resolution()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::DRAFT,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.extension', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::EXPIRED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.extension', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.extension', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.extension', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.extension', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.extension', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_cant_extend_when_remaining_detention_days_is_more_than_the_required_day()
    {
        config(['atc.access.days_remaining' => 3]);

        $user = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        $application = Application::factory()->create([
            'user_id' => $user->id,
            'status' => Application::APPROVED,
            'final_resolution' => now(),
            'when' => now()->subDays(1)->format('Y-m-d H:i'),
        ]);

        $response = $this->post(route('applications.extension', ['application' => $application]));

        $response->assertSessionHas(['flash.banner' => 'You can only apply for extension 3 days before the last day of detention']);
    }

    /** @test */
    public function test_can_extend_application()
    {
        Config::set('atc.access.days_remaining', 13);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
            'final_resolution' => now(),
            'when' => now()->subDay()->format('Y-m-d H:i'),
        ]);

        $this->post(route('applications.extension', ['application' => $application]));

        $this->assertDatabaseHas('applications', [
            'control_number' => $application->control_number,
            'name' => $application->name,
            'rank' => $application->rank,
            'badge_number' => $application->badge_number,
            'unit' => $application->unit,
            'office_address' => $application->office_address,
            'tel' => $application->tel,
            'arrested_firstname' => $application->arrested_firstname,
            'arrested_middlename' => $application->arrested_middlename,
            'arrested_lastname' => $application->arrested_lastname,
            'arrested_suffix' => $application->arrested_suffix,
            'arrested_address' => $application->arrested_address,
            'arrested_tel' => $application->arrested_tel,
            'arrested_pob' => $application->arrested_pob,
            'arrested_dob' => $application->arrested_dob,
            'arrested_sex' => $application->arrested_sex,
            'arrested_status' => $application->arrested_status,
            'arrested_spouse_name' => 'a:1:{i:0;s:11:"spouse name";}',
            'arrested_spouse_address' => 'a:1:{i:0;s:14:"spouse address";}',
            'arrested_weight' => $application->arrested_weight,
            'arrested_height' => $application->arrested_height,
            'arrested_eyes' => $application->arrested_eyes,
            'arrested_hair' => $application->arrested_hair,
            'arrested_complexion' => $application->arrested_complexion,
            'arrested_occupation' => $application->arrested_occupation,
            'arrested_nationality' => $application->arrested_nationality,
            'arrested_tribe' => $application->arrested_tribe,
            'arrested_language' => $application->arrested_language,
            'arrested_educ_attainment' => $application->arrested_educ_attainment,
            'arrested_school_name' => $application->arrested_school_name,
            'arrested_school_address' => $application->arrested_school_address,
            'arrested_marks' => 'a:1:{i:0;s:4:"mole";}',
            'arrested_location_marks' => $application->arrested_location_marks,
            'arrested_defect' => $application->arrested_defect,
            'who' => $application->who,
            'when' => $application->when,
            'where' => $application->where,
            'what' => $application->what,
            'how' => $application->how,
            'other_details' => $application->other_details,
            'arrested_category' => $application->arrested_category,
            'is_informed_of_right' => $application->is_informed_of_right,
            'mental_condition' => $application->mental_condition,
            'physical_condition' => $application->physical_condition,
            'extension_reason' => 'a:1:{i:0;s:1:"A";}',
            'reason_narration' => $application->reason_narration,
            'status' => Application::AVAILABLE,
            'is_extension' => true,
        ]);
    }

    /** @test */
    public function test_cant_extend_application_when_there_is_existing_application()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        $extendedApplication = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
            'is_extension' => true,
        ]);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
            'final_resolution' => now(),
        ]);

        $response = $this->post(route('applications.extension', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_move_all_attachments_after_extension_of_application()
    {
        Config::set('atc.access.days_remaining', 13);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
            'final_resolution' => now(),
            'when' => now()->subDay()->format('Y-m-d H:i'),
        ]);

        $application->addMedia(UploadedFile::fake()->image('mugshot.jpg'))
                    ->toMediaCollection('arrested_picture');
        $application->addMedia(UploadedFile::fake()->image('mugshot.jpg'))
                    ->toMediaCollection('sworn_affidavit');
        $application->addMedia(UploadedFile::fake()->image('mugshot.jpg'))
                    ->toMediaCollection('logbook');
        $application->addMedia(UploadedFile::fake()->image('mugshot.jpg'))
                    ->toMediaCollection('book_sheet');
        $application->addMedia(UploadedFile::fake()->image('mugshot.jpg'))
                    ->toMediaCollection('spot_report');
        $application->addMedia(UploadedFile::fake()->image('mugshot.jpg'))
                    ->toMediaCollection('sworn_affidavit_witness');
        $application->addMedia(UploadedFile::fake()->image('mugshot.jpg'))
                    ->toMediaCollection('warrantless_arrest');
        $application->addMedia(UploadedFile::fake()->image('mugshot.jpg'))
                    ->toMediaCollection('supporting_documents');

        $this->post(route('applications.extension', ['application' => $application]));

        $application = Application::all()->last();

        $arrested_picture = $application->getMediaFile('arrested_picture')->file_name;
        $sworn_affidavit = $application->getMediaFile('sworn_affidavit')->file_name;
        $logbook = $application->getMediaFile('logbook')->file_name;
        $book_sheet = $application->getMediaFile('book_sheet')->file_name;
        $spot_report = $application->getMediaFile('spot_report')->file_name;
        $sworn_affidavit_witness = $application->getMediaFile('sworn_affidavit_witness')->file_name;
        $warrantless_arrest = $application->getMediaFile('warrantless_arrest')->file_name;
        $supporting_documents = $application->getMediaFile('supporting_documents')->file_name;

        Storage::disk('local')->assertExists($application->control_number.'/extension/arrested_picture/'.$arrested_picture);
        Storage::disk('local')->assertExists($application->control_number.'/extension/sworn_affidavit/'.$sworn_affidavit);
        Storage::disk('local')->assertExists($application->control_number.'/extension/logbook/'.$logbook);
        Storage::disk('local')->assertExists($application->control_number.'/extension/book_sheet/'.$book_sheet);
        Storage::disk('local')->assertExists($application->control_number.'/extension/spot_report/'.$spot_report);
        Storage::disk('local')->assertExists($application->control_number.'/extension/sworn_affidavit_witness/'.$sworn_affidavit_witness);
        Storage::disk('local')->assertExists($application->control_number.'/extension/warrantless_arrest/'.$warrantless_arrest);
        Storage::disk('local')->assertExists($application->control_number.'/extension/supporting_documents/'.$supporting_documents);
    }

    /** @test */
    public function test_fire_notification_after_extending_application()
    {
        Config::set('atc.access.days_remaining', 13);

        Bus::fake();

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
            'final_resolution' => now(),
            'when' => now()->subDay()->format('Y-m-d H:i'),
        ]);

        $this->post(route('applications.extension', ['application' => $application]));

        Bus::assertDispatched(\App\Jobs\SendNotificationOnUserForExtensionApplication::class);
    }

    /** @test */
    public function test_fire_event_after_extending_application()
    {
        Config::set('atc.access.days_remaining', 13);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        Event::fake();

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
            'final_resolution' => now(),
            'when' => now()->subDay()->format('Y-m-d H:i'),
        ]);

        $this->post(route('applications.extension', ['application' => $application]));

        Event::assertDispatched(ExtensionRequested::class);
    }
}
