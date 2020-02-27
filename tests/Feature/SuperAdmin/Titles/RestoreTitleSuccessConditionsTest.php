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
class RestoreTitleSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_restore_a_deleted_title()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $title = TitleFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($title);

        $response->assertRedirect(route('titles.index'));
        $this->assertNull($title->fresh()->deleted_at);
    }
}
