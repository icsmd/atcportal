<?php

namespace Tests\Feature\Application;

use App\Events\Application\ApplicationEndorsed;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Storage;
use Tests\TestCase;

class EndorseApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_cant_endorse_application_when_no_endorse_application_permission()
    {
        $applicant = $this->loginAsCustomUser('no permission');

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.endorse', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_cant_endorse_application_when_status_is_not_available()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.endorse_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::DRAFT,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.endorse', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::EXPIRED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.endorse', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.endorse', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.endorse', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.endorse', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::DISAPPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.endorse', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_can_endorse_application_with_remarks()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.endorse_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.endorse', ['application' => $application]), [
            'remarks' => 'remarks',
            'temporary_documents' => [],
        ]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'endorsed_remarks' => 'remarks',
        ]);
    }

    /** @test */
    public function test_can_endorse_application_with_attachments()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.endorse_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $uuid1 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $uuid2 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $response = $this->post(route('applications.endorse', ['application' => $application]), [
            'remarks' => '',
            'temporary_documents' => [$uuid1, $uuid2],
        ]);

        $filenames = $application->fresh()->getFiles('endorsed_documents')->pluck('file_name')->toArray();

        Storage::disk('local')->assertExists($application->control_number.'/endorsed_documents/'.$filenames[0]);
        Storage::disk('local')->assertExists($application->control_number.'/endorsed_documents/'.$filenames[1]);
    }

    /** @test */
    public function test_can_endorse_application_with_attachments_and_remarks()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.endorse_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $uuid1 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $uuid2 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $response = $this->post(route('applications.endorse', ['application' => $application]), [
            'remarks' => 'remarks',
            'temporary_documents' => [$uuid1, $uuid2],
        ]);

        $filenames = $application->fresh()->getFiles('endorsed_documents')->pluck('file_name')->toArray();

        Storage::disk('local')->assertExists($application->control_number.'/endorsed_documents/'.$filenames[0]);
        Storage::disk('local')->assertExists($application->control_number.'/endorsed_documents/'.$filenames[1]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'endorsed_remarks' => 'remarks',
        ]);
    }

    /** @test */
    public function test_fire_notification_after_endorsing_application()
    {
        Bus::fake();

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.endorse_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.endorse', ['application' => $application]), [
            'remarks' => 'remarks',
            'temporary_documents' => [],
        ]);

        Bus::assertDispatched(\App\Jobs\SendNotificationOnUserForEndorsedApplication::class);
    }

    /** @test */
    public function test_fire_event_after_endorsing_application()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.endorse_application'));

        Event::fake();

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.endorse', ['application' => $application]), [
            'remarks' => 'remarks',
            'temporary_documents' => [],
        ]);

        Event::assertDispatched(ApplicationEndorsed::class);
    }
}
