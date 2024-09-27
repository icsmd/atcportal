<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'manage user']);
        Permission::create(['name' => 'send application']);
        Permission::create(['name' => 'restrict view other application']);
        Permission::create(['name' => 'update application']);
        Permission::create(['name' => 'approve application']);
        Permission::create(['name' => 'disapprove application']);
        Permission::create(['name' => 'edit narrative']);
        Permission::create(['name' => 'comment application']);
        Permission::create(['name' => 'view discussion']);
        Permission::create(['name' => 'endorse application']);
        Permission::create(['name' => 'vote application']);
        Permission::create(['name' => 'view vote']);
        Permission::create(['name' => 'provide resolution']);

        // create roles and assign created permissions
        $role = Role::create(['name' => config('atc.access.role.admin')]);
        $role->givePermissionTo(['manage user']);

        $role = Role::create(['name' => config('atc.access.role.applicant')]);
        $role->givePermissionTo(['send application', 'update application', 'restrict view other application']);

        $role = Role::create(['name' => config('atc.access.role.atc_secretariat')])
            ->givePermissionTo([
                'approve application',
                'update application',
                'disapprove application',
                'view discussion',
                'edit narrative',
                'view vote',
                'provide resolution',
            ]);

        $role = Role::create(['name' => config('atc.access.role.review')])
            ->givePermissionTo([
                'view discussion',
                'comment application',
            ]);

        $role = Role::create(['name' => config('atc.access.role.vote')])
            ->givePermissionTo([
                'view vote',
                'vote application',
            ]);
    }
}
