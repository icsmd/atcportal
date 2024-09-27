<?php

namespace Tests\Feature\Application;

use App\Events\Application\ApplicationDisapproved;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Storage;
use Tests\TestCase;

class DisapproveApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_cant_disapprove_application_when_no_disapprove_application_permission()
    {
        $applicant = $this->loginAsCustomUser('no permission');

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.disapprove', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_cant_disapprove_application_when_status_is_not_available_vote_and_endorsing()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.disapprove_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::DRAFT,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.disapprove', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::EXPIRED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.disapprove', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.disapprove', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_can_disapprove_application_with_remarks()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.disapprove_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.disapprove', ['application' => $application]), [
            'remarks' => 'remarks',
            'temporary_documents' => [],
        ]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'disapproved_remarks' => 'remarks',
        ]);
    }

    /** @test */
    public function test_can_disapprove_application_with_attachments()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.disapprove_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $uuid1 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $uuid2 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $response = $this->post(route('applications.disapprove', ['application' => $application]), [
            'disapproved_remarks' => '',
            'temporary_documents' => [$uuid1, $uuid2],
        ]);

        $filenames = $application->fresh()->getFiles('disapproved_documents')->pluck('file_name')->toArray();

        Storage::disk('local')->assertExists($application->control_number.'/disapproved_documents/'.$filenames[0]);
        Storage::disk('local')->assertExists($application->control_number.'/disapproved_documents/'.$filenames[1]);
    }

    /** @test */
    public function test_can_disapprove_application_with_attachments_and_remarks()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.disapprove_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $uuid1 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $uuid2 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $response = $this->post(route('applications.disapprove', ['application' => $application]), [
            'remarks' => 'remarks',
            'temporary_documents' => [$uuid1, $uuid2],
        ]);

        $filenames = $application->fresh()->getFiles('disapproved_documents')->pluck('file_name')->toArray();

        Storage::disk('local')->assertExists($application->control_number.'/disapproved_documents/'.$filenames[0]);
        Storage::disk('local')->assertExists($application->control_number.'/disapproved_documents/'.$filenames[1]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'disapproved_remarks' => 'remarks',
        ]);
    }

    /** @test */
    public function test_fire_notification_after_disapproving_application()
    {
        Bus::fake();

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.disapprove_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.disapprove', ['application' => $application]), [
            'remarks' => 'remarks',
            'temporary_documents' => [],
        ]);

        Bus::assertDispatched(\App\Jobs\SendNotificationOnUserForDisapprovedApplication::class);
    }

    /** @test */
    public function test_fire_event_after_disapproving_application()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.disapprove_application'));

        Event::fake();

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.disapprove', ['application' => $application]), [
            'remarks' => 'remarks',
            'temporary_documents' => [],
        ]);

        Event::assertDispatched(ApplicationDisapproved::class);
    }
}
