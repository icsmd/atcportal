<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        config(['app.env' => 'local']);

        Artisan::call('db:seed');

        \Config::set('media-library.disk_name', 'local');
        \Storage::fake('local');
    }

    protected function getAdmin()
    {
        return User::find(1);
    }

    protected function getApplicant()
    {
        return User::find(2);
    }

    protected function getNewUser()
    {
        return User::find(16);
    }

    protected function loginAsAdmin()
    {
        return $this->loginAs(config('atc.access.role.admin'));
    }

    protected function loginAsApplicant()
    {
        return $this->loginAs(config('atc.access.role.applicant'));
    }

    protected function loginAsNewUser()
    {
        return $this->loginAs('new user');
    }

    /**
     * Create the custom role
     *
     * @return mixed
     */
    protected function getPermission($permissionName)
    {
        $permissions = [];
        foreach ($permissionName as $index => $name) {
            $permissions[$index] = Permission::where('name', $name)->first();

            if (empty($permissions[$index])) {
                $permissions[$index] = Permission::create(['name' => $name]);
            }
        }

        return $permissions;
    }

    /**
     * Login as user with permissions.
     *
     * @param  mixed  $permission
     * @param  array  $attributes
     * @return bool|mixed
     */
    protected function loginAsCustomUser($permissionList, array $attributes = [])
    {
        if (! is_array($permissionList)) {
            $permission[] = $permissionList;
        } else {
            $permission = $permissionList;
        }

        $permission = $this->getPermission($permission);
        $user = User::factory()->create($attributes);
        $user->givePermissionTo($permission);

        $this->actingAs($user);

        return $user;
    }

    /**
     * @return User
     */
    protected function getLatestUser()
    {
        $user = User::all()->last();

        $this->actingAs($user);

        return $user;
    }

    protected function loginAs($role)
    {
        if ($role == config('atc.access.role.admin')) {
            $user = $this->getAdmin();
        } elseif ($role == config('atc.access.role.applicant')) {
            $user = $this->getApplicant();
        } elseif ($role == 'new user') {
            $user = $this->getNewUser();
        }

        $this->actingAs($user);

        return $user;
    }

    protected function logout()
    {
        return auth()->logout();
    }
}
