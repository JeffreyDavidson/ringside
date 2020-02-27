<?php

namespace Tests\Feature\Guest\Stables;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $response = $this->indexRequest('stables');

        $response->assertRedirect(route('login'));
    }
}
