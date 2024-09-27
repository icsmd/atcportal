<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Notifications\NewUserChangePasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_cant_create_user_without_manage_user_permission()
    {
        $this->loginAsCustomUser('no permission');

        $response = $this->post(route('users'), [
            'name' => 'New User',
            'tel' => '09121111111',
            'email' => 'new@new.com',
            'password' => 'secret-password',
            'permissions' => ['send application', 'update application'],
            'active' => false,
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_can_create_user()
    {
        $this->loginAsCustomUser('manage user');

        $response = $this->post(route('users'), [
            'name' => 'New User',
            'tel' => '+639121111111',
            'email' => 'new@new.com',
            'password' => 'secret-password',
            'permissions' => ['send application', 'update application'],
            'active' => false,
        ]);

        $this->assertDatabaseHas('users', [
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
    public function test_send_notification_after_creating_user()
    {
        Notification::fake();

        $this->loginAsCustomUser('manage user');

        $response = $this->post(route('users'), [
            'name' => 'New User',
            'tel' => '+639121111111',
            'email' => 'new@new.com',
            'password' => 'secret-password',
            'permissions' => ['send application', 'update application'],
            'active' => false,
        ]);

        $user = User::orderBy('id', 'desc')->first();

        Notification::assertSentTo(
            [$user], NewUserChangePasswordNotification::class
        );
    }
}
