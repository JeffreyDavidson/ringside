<?php

namespace Tests\Feature\User\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TitleFactory;

/**
 * @group titles
 * @group users
 */
class RestoreTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_restore_a_deleted_title()
    {
        $this->actAs(Role::BASIC);
        $title = TitleFactory::new()->softDeleted()->create();

        $response = $this->put(route('titles.restore', $title));

        $response->assertForbidden();
    }
}
