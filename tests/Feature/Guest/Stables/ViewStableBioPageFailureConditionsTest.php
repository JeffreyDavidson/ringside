<?php

namespace Tests\Feature\Guest\Stables;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\StableFactory;
use Tests\TestCase;

/**
 * @group stables
 * @group guests
 * @group roster
 */
class ViewStableBioPageFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_view_a_stable_profile()
    {
        $stable = StableFactory::new()->create();

        $response = $this->showRequest($stable);

        $response->assertRedirect(route('login'));
    }
}
