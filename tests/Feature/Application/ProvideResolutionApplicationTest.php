<?php

namespace Tests\Feature\Application;

use App\Events\Application\ResolutionUploaded;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Storage;
use Tests\TestCase;

class ProvideResolutionApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_cant_provide_resolution_application_when_no_provide_resolution_application_permission()
    {
        $applicant = $this->loginAsCustomUser('no permission');

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.resolution', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_cant_provide_resolution_application_when_status_is_not_approved()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.provide_resolution'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::DRAFT,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.resolution', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::EXPIRED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.resolution', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.resolution', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.resolution', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.resolution', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_can_provide_resolution_application_with_final_resolution()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.provide_resolution'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.resolution', ['application' => $application]), [
            'resolution_file' => UploadedFile::fake()->image('mugshot.jpg'),
            'temporary_documents' => [],
        ]);

        $resolution = $application->getMediaFile('resolution')->file_name;

        Storage::disk('local')->assertExists($application->control_number.'/resolution/'.$resolution);
    }

    /** @test */
    public function test_can_provide_resolution_application_with_attachments()
    {
        $this->withoutMiddleware(\App\Http\Middleware\CheckExpiredApplication::class);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.provide_resolution'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $uuid1 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $uuid2 = $this->post(route('upload.temporary'), [
            'document' => UploadedFile::fake()->image('test.jpg'),
        ])->getContent();

        $response = $this->post(route('applications.resolution', ['application' => $application]), [
            'resolution_file' => UploadedFile::fake()->image('mugshot.jpg'),
            'temporary_documents' => [$uuid1, $uuid2],
        ]);

        $filenames = $application->fresh()->getFiles('resolution_documents')->pluck('file_name')->toArray();

        Storage::disk('local')->assertExists($application->control_number.'/resolution_documents/'.$filenames[0]);
        Storage::disk('local')->assertExists($application->control_number.'/resolution_documents/'.$filenames[1]);
    }

    /** @test */
    public function test_fire_notification_after_provide_resolution_application()
    {
        Bus::fake();

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.provide_resolution'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.resolution', ['application' => $application]), [
            'resolution_file' => UploadedFile::fake()->image('mugshot.jpg'),
            'temporary_documents' => [],
        ]);

        Bus::assertDispatched(\App\Jobs\SendNotificationOnUserForCompletedApplication::class);
    }

    /** @test */
    public function test_fire_event_after_provide_resolution_application()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.provide_resolution'));

        Event::fake();

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.resolution', ['application' => $application]), [
            'resolution_file' => UploadedFile::fake()->image('mugshot.jpg'),
            'temporary_documents' => [],
        ]);

        Event::assertDispatched(ResolutionUploaded::class);
    }
}
