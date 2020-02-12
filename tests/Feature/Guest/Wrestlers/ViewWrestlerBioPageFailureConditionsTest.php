<?php

namespace Tests\Feature\Guest\Wrestlers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use WrestlerFactory;

/**
 * @group wrestlers
 * @group guests
 */
class ViewWrestlerBioPageFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_view_a_wrestler_profile()
    {
        $wrestler = WrestlerFactory::new()->create();

        $response = $this->showRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
