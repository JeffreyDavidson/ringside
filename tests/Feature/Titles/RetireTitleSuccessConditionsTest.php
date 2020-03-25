<?php

namespace Tests\Feature\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TitleFactory;
use Tests\TestCase;

/**
 * @group titles
 */
class RetireTitleSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_retire_a_competable_title()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->competable()->create();

        $response = $this->retireRequest($title);

        $response->assertRedirect(route('titles.index'));
        $this->assertEquals(now()->toDateTimeString(), $title->fresh()->currentRetirement->started_at);
    }
}
