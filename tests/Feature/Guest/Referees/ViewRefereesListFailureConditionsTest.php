<?php

namespace Tests\Feature\Guest\Referees;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $response = $this->get(route('referees.index'));

        $response->assertRedirect(route('login'));
    }
}
