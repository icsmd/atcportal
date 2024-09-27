<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ViewUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_cant_access_user_list_without_manage_user_permission()
    {
        $this->loginAsCustomUser('no permission');

        $user = User::factory()->create();

        $response = $this->get(route('users'));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_can_access_user_list_wit_manage_user_permission()
    {
        $this->loginAsCustomUser('manage user');

        $response = $this->get(route('users'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('User/Index')
                ->has('users.data', 6)
            );
    }
}
