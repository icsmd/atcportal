<?php

namespace Tests\Feature\Application;

use App\Events\Application\CommentPosted;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_cant_comment_application_when_no_comment_application_permission()
    {
        $applicant = $this->loginAsCustomUser('no permission');

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.comment', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_cant_comment_application_when_status_is_not_endorsing()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.comment_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::DRAFT,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.comment', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::EXPIRED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.comment', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.comment', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.comment', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.comment', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::DISAPPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.comment', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_can_comment_application()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.comment_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.comment', ['application' => $application]), [
            'body' => 'body',
        ]);

        $this->assertDatabaseHas('comments', [
            'application_id' => $application->id,
            'user_id' => $applicant->id,
            'body' => 'body',
        ]);
    }

    /** @test */
    public function test_fire_notification_after_commenting_application()
    {
        Bus::fake();

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.comment_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.comment', ['application' => $application]), [
            'body' => 'body',
        ]);

        Bus::assertDispatched(\App\Jobs\SendCommentNotification::class);
    }

    /** @test */
    public function test_fire_event_after_commenting_application()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.comment_application'));

        Event::fake();

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.comment', ['application' => $application]), [
            'body' => 'body',
        ]);

        Event::assertDispatched(CommentPosted::class);
    }
}
