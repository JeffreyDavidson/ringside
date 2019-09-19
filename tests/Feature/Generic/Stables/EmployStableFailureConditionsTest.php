<?php

namespace Tests\Feature\Generic\Stables;

use Tests\TestCase;
use App\Models\Stable;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group stables
 * @group generics
 * @group roster
 */
class EmployStableFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_stable_cannot_be_employed()
    {
        $this->actAs('administrator');
        $stable = factory(Stable::class)->states('bookable')->create();

        $response = $this->put(route('stables.employ', $stable));

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_stable_cannot_be_employed()
    {
        $this->actAs('administrator');
        $stable = factory(Stable::class)->states('retired')->create();

        $response = $this->put(route('stables.employ', $stable));

        $response->assertForbidden();
    }
}
