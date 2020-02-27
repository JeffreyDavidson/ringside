<?php

namespace Tests\Feature\User\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 * @group users
 */
class UnretireTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_unretire_a_retired_title()
    {
        $this->actAs(Role::BASIC);
        $title = TitleFactory::new()->retired()->create();

        $response = $this->unretireRequest($title);

        $response->assertForbidden();
    }
}
