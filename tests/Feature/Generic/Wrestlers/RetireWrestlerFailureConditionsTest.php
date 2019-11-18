<?php

namespace Tests\Feature\Generic\Wrestlers;

use App\Models\Wrestler;
use Tests\TestCase;
use App\Exceptions\CannotBeRetiredException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group generics
 * @group roster
 */
class RetireWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_already_retired_wrestler_cannot_be_retired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRetiredException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $response = $this->retireRequest($wrestler);

        $response->assertForbidden();
    }
}
