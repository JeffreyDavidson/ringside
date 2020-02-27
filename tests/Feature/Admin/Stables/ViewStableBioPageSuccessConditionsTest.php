<?php

namespace Tests\Feature\Admin\Stables;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\StableFactory;
use Tests\TestCase;

/**
 * @group stables
 * @group admins
 * @group roster
 */
class ViewStableBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_a_stable_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $stable = StableFactory::new()->create();

        $response = $this->showRequest($stable);

        $response->assertViewIs('stables.show');
        $this->assertTrue($response->data('stable')->is($stable));
    }
}
