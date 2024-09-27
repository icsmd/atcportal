<?php

namespace Tests\Feature\Application;

use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BriefNarrativeApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_cant_update_brief_narrative_when_no_edit_narrative_permission()
    {
        $applicant = $this->loginAsCustomUser('no permission');

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->patch(route('appliations.narrative', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_cant_update_brief_narrative_when_status_is_approved_or_expired_or_disapproved()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.edit_narrative'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->patch(route('appliations.narrative', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::EXPIRED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->patch(route('appliations.narrative', ['application' => $application]));

        $response->assertStatus(403);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::DISAPPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->patch(route('appliations.narrative', ['application' => $application]));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_can_edit_narrative()
    {
        $applicant = $this->loginAsCustomUser(config('atc.access.permission.edit_narrative'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->patch(route('appliations.narrative', ['application' => $application]), [
            'reason_narration' => 'new narrative',
        ]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'reason_narration' => 'new narrative',
        ]);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->patch(route('appliations.narrative', ['application' => $application]), [
            'reason_narration' => 'new narrative',
        ]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'reason_narration' => 'new narrative',
        ]);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->patch(route('appliations.narrative', ['application' => $application]), [
            'reason_narration' => 'new narrative',
        ]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'reason_narration' => 'new narrative',
        ]);

        $application = Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::DRAFT,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->patch(route('appliations.narrative', ['application' => $application]), [
            'reason_narration' => 'new narrative',
        ]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'reason_narration' => 'new narrative',
        ]);
    }
}
