<?php

namespace Tests\Feature\Generic\Wrestlers;

use App\Models\Wrestler;
use Tests\TestCase;
use App\Exceptions\CannotBeSuspendedException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group generics
 * @group roster
 */
class SuspendWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_already_suspendeded_wrestler_cannot_be_suspended()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeSuspendedException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $response = $this->suspendRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_wrestler_cannot_be_suspended()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeSuspendedException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $response = $this->suspendRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_suspended()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeSuspendedException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $response = $this->suspendRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function an_injured_wrestler_cannot_be_suspended()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeSuspendedException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $response = $this->suspendRequest($wrestler);

        $response->assertForbidden();
    }
}
