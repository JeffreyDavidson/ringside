<?php

namespace Tests\Feature\Admin\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group admins
 * @group roster
 */
class ViewRefereeBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_a_referee_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->create();

        $response = $this->showRequest($referee);

        $response->assertViewIs('referees.show');
        $this->assertTrue($response->data('referee')->is($referee));
    }
}
