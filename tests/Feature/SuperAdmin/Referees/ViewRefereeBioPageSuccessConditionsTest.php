<?php

namespace Tests\Feature\SuperAdmin\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group superadmins
 * @group roster
 */
class ViewRefereeBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_view_a_referee_profile()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $referee = RefereeFactory::new()->create();

        $response = $this->showRequest($referee);

        $response->assertViewIs('referees.show');
        $this->assertTrue($response->data('referee')->is($referee));
    }
}
