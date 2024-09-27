<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_roles_with_permissions_except_admin()
    {
        $user = $this->loginAsCustomUser('test');

        $response = $this->get('/roles');

        $this->assertEquals(4, count($response->getData()));
    }
}
