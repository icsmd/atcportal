<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_cant_update_user_without_manage_user_permission()
    {
        $this->loginAsCustomUser('no permission');

        $user = User::factory()->create();

        $response = $this->put(route('users.update', ['user' => $user]), [
            'name' => 'New User',
            'tel' => '+639121111111',
            'email' => 'new@new.com',
            'password' => 'secret-password',
            'permissions' => ['send application', 'update application'],
            'active' => false,
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_can_update_user()
    {
        $this->loginAsCustomUser('manage user');

        $user = User::factory()->create();

        $response = $this->put(route('users.update', ['user' => $user]), [
            'name' => 'New User',
            'tel' => '+639121111111',
            'email' => 'new@new.com',
            'password' => 'secret-password',
            'permissions' => ['send application', 'update application'],
            'active' => false,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New User',
            'tel' => '+639121111111',
            'email' => 'new@new.com',
            'active' => false,
        ]);

        $user = User::orderBy('id', 'desc')->first();

        $permissions = $user->permissions()->pluck('name')->toArray();

        $this->assertTrue(Hash::check('secret-password', $user->password));
        $this->assertEquals($permissions, ['send application', 'update application']);
    }

    /** @test */
    public function test_can_update_user_without_password()
    {
        $this->loginAsCustomUser('manage user');

        $user = User::factory()->create();

        $response = $this->put(route('users.update', ['user' => $user]), [
            'name' => 'New User',
            'tel' => '+639121111111',
            'email' => 'new@new.com',
            'permissions' => ['send application', 'update application'],
            'active' => false,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New User',
            'tel' => '+639121111111',
            'email' => 'new@new.com',
            'active' => false,
        ]);

        $user = User::orderBy('id', 'desc')->first();

        $permissions = $user->permissions()->pluck('name')->toArray();
        $this->assertEquals($permissions, ['send application', 'update application']);
    }

    /** @test */
    public function test_cant_update_user_own_account()
    {
        $user = $this->loginAsCustomUser('manage user');

        $response = $this->put(route('users.update', ['user' => $user]), [
            'name' => 'New User',
            'tel' => '+639121111111',
            'email' => 'new@new.com',
            'password' => 'secret-password',
            'permissions' => ['send application', 'update application'],
            'active' => false,
        ]);

        $response->assertStatus(403);
    }
}
