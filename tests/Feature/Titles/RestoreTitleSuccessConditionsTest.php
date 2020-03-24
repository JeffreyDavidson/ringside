<?php

namespace Tests\Feature\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 */
class RestoreTitleSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_restore_a_deleted_title()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($title);

        $response->assertRedirect(route('titles.index'));
        $this->assertNull($title->fresh()->deleted_at);
    }
}
