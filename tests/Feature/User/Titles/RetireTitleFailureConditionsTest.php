<?php

namespace Tests\Feature\User\Titles;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group titles
 * @group users
 */
class RetireTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_retire_a_competable_title()
    {
        $this->actAs('basic-user');
        $title = factory(Title::class)->states('competable')->create();

        $response = $this->put(route('titles.retire', $title));

        $response->assertForbidden();
    }
}
