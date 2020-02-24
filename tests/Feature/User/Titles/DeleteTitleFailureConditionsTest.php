<?php

namespace Tests\Feature\Guest\Titles;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group titles
 * @group users
 */
class DeleteTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_delete_a_title()
    {
        $this->actAs(Role::BASIC);
        $title = factory(Title::class)->create();

        $response = $this->delete(route('titles.destroy', $title));

        $response->assertForbidden();
    }
}
