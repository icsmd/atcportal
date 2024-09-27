<?php

namespace Tests\Feature\Application;

use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ViewApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_only_view_own_application_if_have_restrict_permission()
    {
        $this->withoutMiddleware(\App\Http\Middleware\CheckExpiredApplication::class);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.restrict_view'));

        Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        Application::factory(4)->create([
            'user_id' => 50,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $this->get(route('applications'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Application/Index')
                ->has('applications.data', 4)
            );
    }

    /** @test */
    public function test_can_view_all_application_if_dont_have_restrict_permission()
    {
        $this->withoutMiddleware(\App\Http\Middleware\CheckExpiredApplication::class);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::ENDORSING,
            'reason_narration' => 'somewhat error',
        ]);

        Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::VOTING,
            'reason_narration' => 'somewhat error',
        ]);

        Application::factory()->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::APPROVED,
            'reason_narration' => 'somewhat error',
        ]);

        Application::factory(4)->create([
            'user_id' => 50,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $this->get(route('applications'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Application/Index')
                ->has('applications.data', 8)
            );
    }

    /** @test */
    public function test_only_you_can_view_own_drafts()
    {
        $this->withoutMiddleware(\App\Http\Middleware\CheckExpiredApplication::class);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        Application::factory(3)->create([
            'user_id' => $applicant->id,
            'control_number' => 'ATC-001',
            'status' => Application::DRAFT,
            'reason_narration' => 'somewhat error',
        ]);

        Application::factory(4)->create([
            'user_id' => $applicant->id + 1,
            'control_number' => 'ATC-001',
            'status' => Application::DRAFT,
            'reason_narration' => 'somewhat error',
        ]);

        $this->get(route('applications'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Application/Index')
                ->has('applications.data', 3)
            );
    }

    /** @test */
    public function test_cant_access_others_application_if_have_restrict_permission()
    {
        $this->withoutMiddleware(\App\Http\Middleware\CheckExpiredApplication::class);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.restrict_view'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id + 1,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->get(route('applications.show', ['application' => $application]));
        $response->assertStatus(404);
    }

    /** @test */
    public function test_can_access_others_application_if_dont_have_restrict_permission()
    {
        $this->withoutMiddleware(\App\Http\Middleware\CheckExpiredApplication::class);

        $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

        $application = Application::factory()->create([
            'user_id' => $applicant->id + 1,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $response = $this->get(route('applications.show', ['application' => $application]));
        $response->assertOk();
    }

    /** @test */
    public function test_cant_see_and_access_application_if_dont_have_permission()
    {
        return $this->markTestSkipped('No Permission User cant access applications');

        $this->withoutMiddleware(\App\Http\Middleware\CheckExpiredApplication::class);

        $applicant = $this->loginAsCustomUser('');

        Application::factory(4)->create([
            'user_id' => 50,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $application = Application::factory()->create([
            'user_id' => 50,
            'control_number' => 'ATC-001',
            'status' => Application::AVAILABLE,
            'reason_narration' => 'somewhat error',
        ]);

        $this->get(route('applications'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Application/Index')
                ->has('applications.data', 0)
            );

        $response = $this->get(route('applications.show', ['application' => $application]));
        $response->assertStatus(404);
    }
}
