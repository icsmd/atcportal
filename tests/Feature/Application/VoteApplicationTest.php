<?php

namespace Tests\Feature\Application;

use App\Events\Application\ApplicationDisapproved;
use App\Events\Application\ApplicationVoted;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class VoteApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_cant_vote_application_when_no_vote_application_permission()
    {
        $applicant = $this->loginAsCustomUser('no permission');

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_cant_vote_application_when_status_is_not_voting()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.vote_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::DRAFT,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::EXPIRED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::DISAPPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_can_vote_application()
    {
        config(['atc.access.majority_count' => 5]);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.vote_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]), [
            'message' => 'message',
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('votes', [
            'application_id' => $application->id,
            'user_id' => $applicant->id,
            'message' => 'message',
            'status' => 'approved',
        ]);
    }

    /** @test */
    public function test_cant_vote_multiple_application()
    {
        config(['atc.access.majority_count' => 5]);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.vote_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]), [
            'message' => 'message',
            'status' => 'approved',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]), [
            'message' => 'message',
            'status' => 'disapproved',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_change_status_when_reach_majority_vote()
    {
        config(['atc.access.majority_count' => 1]);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.vote_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        $this->post(route('applications.vote', ['application' => $application]), [
            'message' => 'message',
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'approved',
        ]);
    }

    /** @test */
    public function test_fire_event_after_voting_application_if_approve()
    {
        config(['atc.access.majority_count' => 2]);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.vote_application'));

        Event::fake();

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]), [
            'message' => 'message',
            'status' => 'approved',
        ]);

        Event::assertDispatched(ApplicationVoted::class);
    }

    /** @test */
    public function test_fire_event_after_voting_application_if_disapprove()
    {
        config(['atc.access.majority_count' => 2]);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.vote_application'));

        Event::fake();

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]), [
            'message' => 'message',
            'status' => 'disapproved',
        ]);

        Event::assertDispatched(ApplicationVoted::class);
        // Event::assertDispatched(ApplicationDisapproved::class);
    }

    /** @test */
    public function test_fire_notification_after_voting_application()
    {
        Bus::fake();

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.vote_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->post(route('applications.vote', ['application' => $application]), [
            'message' => 'message',
            'status' => 'disapproved',
        ]);

        Bus::assertDispatched(\App\Jobs\SendVoteNotification::class);
    }
}
