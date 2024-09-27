<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    // use RefreshDatabase;

    // /** @test */
    // public function able_to_see_list_of_users_if_have_manage_user_permission()
    // {
    //     $user = $this->loginAsCustomUser('manage user');

    //     $response = $this->get('/users');

    //     $this->assertEquals(6,count($response->getData()));
    // }

    // /** @test */
    // public function dont_display_admin_account_if_not_admin()
    // {
    //     $user = $this->loginAsCustomUser('manage user');

    //     $response = $this->get('/users');

    //     $this->assertEquals(6,count($response->getData()));
    // }

    // /** @test */
    // public function cant_see_list_of_users_if_doesnt_have_manage_user_permission()
    // {
    //     $user = $this->loginAsCustomUser('none');

    //     $response = $this->get('/users');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function able_to_create_user_if_have_manage_user_permission()
    // {
    //     $this->loginAsAdmin();

    //     $response = $this->post('/users',[
    //         'name'          => 'New User',
    //         'tel'           => '09121111111',
    //         'email'         => 'new@new.com',
    //         'password'      => 'secret-password',
    //         'permissions'   => ['send application','update application'],
    //         'active'        => false
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('users',[
    //         'name'      => 'New User',
    //         'tel'       => '09121111111',
    //         'email'     => 'new@new.com',
    //         'active'    => false
    //     ]);

    //     $user = User::orderBy('id','desc')->first();

    //     $this->assertTrue(Hash::check('secret-password', $user->password));
    // }

    // /** @test */
    // public function able_to_choose_role_and_add_another_permission()
    // {
    //     $user = $this->loginAsCustomUser('manage user');

    //     $response = $this->post('/users',[
    //         'name'          => 'New User',
    //         'tel'           => '09121111111',
    //         'password'      => 'new-password',
    //         'email'         => 'new@new.com',
    //         'permissions'   => ['comment application'],
    //         'active'        => true
    //     ]);

    //     $this->post('/logout');
    //     $this->app->get('auth')->forgetGuards();

    //     $user = $this->getLatestUser();

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status'  => Application::ENDORSING
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/comment',[
    //         'body' => 'test comment'
    //     ]);

    //     $response->assertOk();
    // }

    // /** @test */
    // public function cant_update_user_if_dont_have_manage_user_permission()
    // {
    //     $user = $this->loginAsCustomUser('none');

    //     $response = $this->post('/users/'.$user->id.'/update',[
    //         'name'          => 'New User',
    //         'tel'           => '09121111111',
    //         'email'         => 'new@new.com',
    //         'permissions'   => ['send application','update application'],
    //         'active'        => true
    //     ]);

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function cant_update_own_account()
    // {
    //     $user = $this->loginAsCustomUser('manage user');

    //     $response = $this->post('/users/'.$user->id.'/update',[
    //         'name'          => 'Update User',
    //         'tel'           => '09121111111',
    //         'email'         => 'new@new.com',
    //         'permissions'   => ['send application','update application'],
    //         'active'        => true
    //     ]);

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function can_update_user_if_have_manage_user_permission()
    // {
    //     $user = $this->loginAsCustomUser('manage user');

    //     $response = $this->post('/users/3/update',[
    //         'name'          => 'Update User',
    //         'tel'           => '09121111111',
    //         'email'         => 'new@new.com',
    //         'permissions'   => ['send application','update application'],
    //         'active'        => true
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('users',[
    //         'id'            => 3,
    //         'name'          => 'Update User',
    //         'tel'           => '09121111111',
    //         'email'         => 'new@new.com',
    //         'active'        => true
    //     ]);

    //     $user = User::find(3);

    //     $this->assertTrue(Hash::check('secret', $user->password));
    // }

    // /** @test */
    // public function can_update_users_password()
    // {
    //     $user = $this->loginAsCustomUser('manage user');

    //     $response = $this->post('/users/3/update',[
    //         'name'          => 'Update User',
    //         'tel'           => '09121111111',
    //         'email'         => 'new@new.com',
    //         'password'      => 'new-password',
    //         'permissions'   => ['send application','update application'],
    //         'active'        => true
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('users',[
    //         'id'            => 3,
    //         'name'          => 'Update User',
    //         'tel'           => '09121111111',
    //         'email'         => 'new@new.com',
    //         'active'        => true
    //     ]);

    //     $user = User::find(3);

    //     $this->assertTrue(Hash::check('new-password', $user->password));
    // }

    // /** @test */
    // public function user_can_update_info()
    // {
    //     $user = $this->loginAsApplicant();

    //     $response = $this->patch('/user',[
    //         'tel'   => '0977755666',
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('users',[
    //         'id'    => $user->id,
    //         'name'  => $user->name,
    //         'tel'   => '0977755666',
    //     ]);
    // }

    // /** @test */
    // public function user_can_change_its_own_password()
    // {
    //     $user = $this->loginAsApplicant();

    //     $response = $this->post('/change/password',[
    //         'current_password'      => 'secret',
    //         'password'              => 'new-password',
    //         'password_confirmation' => 'new-password'
    //     ]);

    //     $response->assertOk();

    //     $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    // }

    // /** @test */
    // public function user_cant_change_its_own_password_by_using_leaked_passwords()
    // {
    //     $user = $this->loginAsApplicant();

    //     $response = $this->post('/change/password',[
    //         'current_password'      => 'secret',
    //         'password'              => '123123123',
    //         'password_confirmation' => '123123123'
    //     ]);

    //     $response->assertStatus(302);
    // }

    // /** @test */
    // public function cant_change_password_if_current_password_is_wrong()
    // {
    //     $user = $this->loginAsApplicant();

    //     $response = $this->post('/change/password',[
    //         'current_password'      => 'wrong password',
    //         'password'              => 'new-password',
    //         'password_confirmation' => 'new-password'
    //     ]);

    //     $response->assertStatus(302);
    // }

    // /** @test */
    // public function user_can_change_its_font_size()
    // {
    //     $user = $this->loginAsApplicant();

    //     $response = $this->post('/font',[
    //         'font_size' => User::FONT_LARGE,
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('users',[
    //         'id'        => $user->id,
    //         'font_size' => User::FONT_LARGE
    //     ]);
    // }
}
