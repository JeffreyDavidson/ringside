<?php

namespace Tests\Feature\Guest\Titles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TitleFactory;
use Tests\TestCase;

/**
 * @group titles
 * @group guests
 */
class DeleteTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_delete_a_title()
    {
        $title = TitleFactory::new()->create();

        $response = $this->deleteRequest($title);

        $response->assertRedirect(route('login'));
    }
}
