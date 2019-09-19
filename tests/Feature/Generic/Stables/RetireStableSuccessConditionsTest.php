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
class RetireStableSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function retiring_a_stable_also_retires_its_members()
    {
        $this->actAs('administrator');
        $stable = factory(Stable::class)->states('bookable')->create();

        $response = $this->put(route('stables.retire', $stable));

        tap($stable->fresh(), function ($stable) {
            $this->assertTrue($stable->is_retired);
            $this->assertTrue($stable->previousMembers->every->is_retired);
        });
    }
}
