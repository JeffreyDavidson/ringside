<?php

namespace Tests\Feature\Generic\Wrestlers;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Exceptions\CannotBeEmployedException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group generics
 * @group roster
 */
class EmployWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_wrestler_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $response = $this->employRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_wrestler_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $response = $this->employRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_wrestler_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $response = $this->employRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function an_injured_wrestler_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $response = $this->employRequest($wrestler);

        $response->assertForbidden();
    }
}
