<?php

namespace Tests\Feature\Generic\Stables;

use Tests\TestCase;
use App\Models\Stable;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group stables
 * @group generics
 */
class RetireStableSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function retiring_a_stable_also_retires_its_members()
    {
        $this->actAs('administrator');
        $stable = factory(Stable::class)->states('bookabe')->create();

        $response = $this->put(route('stables.retire', $stable));

        tap($stable->fresh(), function ($stable) {
            $this->assertTrue($stable->members->each->is_retired);
        });
    }
}
