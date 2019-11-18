<?php

namespace Tests\Feature\Generic\Wrestlers;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Exceptions\CannotBeUnretiredException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group generics
 * @group roster
 */
class UnretireWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_wrestler_cannot_be_unretired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $response = $this->unretireRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_unretired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $response = $this->unretireRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function an_injured_wrestler_cannot_be_unretired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $response = $this->unretireRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_wrestler_cannot_be_unretired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $response = $this->unretireRequest($wrestler);
    }
}
