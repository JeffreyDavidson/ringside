<?php

namespace Tests\Feature\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group roster
 */
class ViewRefereeBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider adminRoles
     */
    public function administrators_can_view_a_referee_profile($adminRoles)
    {
        $this->actAs($adminRoles);
        $referee = RefereeFactory::new()->create();

        $response = $this->showRequest($referee);

        $response->assertViewIs('referees.show');
        $this->assertTrue($response->data('referee')->is($referee));
    }

    /** @test */
    public function a_referees_data_can_be_seen_on_their_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $referee = RefereeFactory::new()
            ->bookable()
            ->create(['first_name' => 'John', 'last_name' => 'Smith']);

        $response = $this->showRequest($referee);

        $response->assertSee('John Smith');
    }

    public function adminRoles()
    {
        return [
            [Role::ADMINISTRATOR],
            [Role::SUPER_ADMINISTRATOR],
        ];
    }
}
