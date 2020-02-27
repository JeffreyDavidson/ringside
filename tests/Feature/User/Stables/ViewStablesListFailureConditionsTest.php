<?php

namespace Tests\Feature\User\Stables;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group stables
 * @group users
 * @group roster
 */
class ViewStablesListFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_view_stables_page()
    {
        $this->actAs(Role::BASIC);

        $response = $this->indexRequest('stable');

        $response->assertForbidden();
    }
}
