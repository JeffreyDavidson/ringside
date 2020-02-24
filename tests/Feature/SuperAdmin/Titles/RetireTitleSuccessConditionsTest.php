<?php

namespace Tests\Feature\SuperAdmin\Titles;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group titles
 * @group superadmins
 */
class RetireTitleSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_retire_a_competable_title()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $title = factory(Title::class)->states('competable')->create();

        $response = $this->put(route('titles.retire', $title));

        $response->assertRedirect(route('titles.index'));
        $this->assertEquals(now()->toDateTimeString(), $title->fresh()->currentRetirement->started_at);
    }
}
