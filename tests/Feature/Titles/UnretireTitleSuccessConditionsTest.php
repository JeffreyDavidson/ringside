<?php

namespace Tests\Feature\Admin\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 * @group admins
 */
class UnretireTitleSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_unretire_a_retired_title()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->retired()->create();

        $response = $this->unretireRequest($title);

        $response->assertRedirect(route('titles.index'));
        $this->assertEquals(now()->toDateTimeString(), $title->fresh()->retirements()->latest()->first()->ended_at);
    }
}
