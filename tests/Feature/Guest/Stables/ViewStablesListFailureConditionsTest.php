<?php

namespace Tests\Feature\Guest\Stables;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group stables
 * @group guests
 * @group roster
 */
class ViewStablesListFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_view_stables_page()
    {
        $response = $this->get(route('stables.index'));

        $response->assertRedirect(route('login'));
    }
}
