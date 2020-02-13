<?php

namespace Tests\Feature\Guest\Referees;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group referees
 * @group guests
 * @group roster
 */
class ViewRefereesListFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_view_referees_page()
    {
        $response = $this->indexRequest('referee');

        $response->assertRedirect(route('login'));
    }
}
