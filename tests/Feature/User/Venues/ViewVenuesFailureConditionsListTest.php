<?php

namespace Tests\Feature\User\Venues;

use Tests\TestCase;

/**
 * @group venues
 * @group users
 */
class ViewVenuesListFailureConditionsTest extends TestCase
{
    /** @test */
    public function a_basic_user_cannot_view_venues_page()
    {
        $this->actAs('basic-user');

        $response = $this->indexRequest('venues');

        $response->assertForbidden();
    }
}
