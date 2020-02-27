<?php

namespace Tests\Feature\SuperAdmin\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 * @group superadmins
 */
class UnretireTitleSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_unretire_a_retired_title()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $title = TitleFactory::new()->retired()->create();

        $response = $this->unretireRequest($title);

        $response->assertRedirect(route('titles.index'));
        $this->assertEquals(now()->toDateTimeString(), $title->fresh()->retirements()->latest()->first()->ended_at);
    }
}
