<?php

namespace Tests\Feature\Generic\Referees;

use App\Models\Referee;
use Tests\TestCase;
use App\Exceptions\CannotBeRetiredException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group generics
 * @group roster
 */
class RetireRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_already_retired_referee_cannot_be_retired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRetiredException::class);

        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('retired')->create();

        $response = $this->retireRequest($referee);
    }

    /** @test */
    public function a_pending_introduction_retired_referee_cannot_be_retired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRetiredException::class);

        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('retired')->create();

        $response = $this->retireRequest($referee);
    }
}
