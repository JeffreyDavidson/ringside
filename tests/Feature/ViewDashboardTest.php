<?php

namespace Tests\Feature;

use App\Enums\Role;
use Tests\TestCase;

class ViewDashboardTest extends TestCase
{
    /**
     * @test
     */
    public function an_administrator_can_access_the_dashboard_if_signed_in()
    {
        $this->actAs(Role::administrator());

        $response = $this->get(route('dashboard'));

        $response->assertViewIs('dashboard');
    }
}
