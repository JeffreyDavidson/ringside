<?php

namespace Tests\Feature\Admin\Titles;

use App\Enums\Role;
use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TitleFactory;

/**
 * @group titles
 * @group admins
 */
class RetireTitleSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_retire_a_competable_title()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->competable()->create();
        dd($title);

        $response = $this->retireRequest($title);
        dd($response);

        $response->assertRedirect(route('titles.index'));
        $this->assertEquals(now()->toDateTimeString(), $title->fresh()->currentRetirement->started_at);
    }
}
