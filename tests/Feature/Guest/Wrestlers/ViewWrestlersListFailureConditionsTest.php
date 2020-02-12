<?php

namespace Tests\Feature\Guest\Wrestlers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group guests
 */
class ViewWrestlersListFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_view_wrestlers_page()
    {
        $response = $this->indexRequest('wrestler');

        $response->assertRedirect(route('login'));
    }
}
