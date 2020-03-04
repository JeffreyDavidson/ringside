<?php

namespace Tests\Feature\Generic\Stables;

use App\Models\Stable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group stables
 * @group generics
 * @group roster
 */
class ViewStableBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_stables_name_can_be_seen_on_their_profile()
    {
        $this->actAs('administrator');
        $stable = factory(Stable::class)->create(['name' => 'Example Stable Name']);

        $response = $this->get(route('stables.show', $stable));

        $response->assertSee('Example Stable Name');
    }
}
