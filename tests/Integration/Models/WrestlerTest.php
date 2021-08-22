<?php

namespace Tests\Integration\Models;

use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group wrestlers
 */
class WrestlerTest extends TestCase
{
    use RefreshDatabase;

    private $futureEmployedWrestler;
    private $bookableWrestler;
    private $injuredWrestler;
    private $suspendedWrestler;
    private $retiredWrestler;
    private $releasedWrestler;

    public function setUp(): void
    {
        parent::setUp();

        $this->futureEmployedWrestler = Wrestler::factory()->withFutureEmployment()->create();
        $this->bookableWrestler = Wrestler::factory()->bookable()->create();
        $this->injuredWrestler = Wrestler::factory()->injured()->create();
        $this->suspendedWrestler = Wrestler::factory()->suspended()->create();
        $this->retiredWrestler = Wrestler::factory()->retired()->create();
        $this->releasedWrestler = Wrestler::factory()->released()->create();
    }

    /**
     * @test
     */
    public function it_can_get_bookable_wrestlers()
    {
        $bookableWrestlers = Wrestler::bookable()->get();

        $this->assertCount(1, $bookableWrestlers);
        $this->assertTrue($bookableWrestlers->contains($this->bookableWrestler));
        $this->assertFalse($bookableWrestlers->contains($this->futureEmployedWrestler));
        $this->assertFalse($bookableWrestlers->contains($this->injuredWrestler));
        $this->assertFalse($bookableWrestlers->contains($this->suspendedWrestler));
        $this->assertFalse($bookableWrestlers->contains($this->retiredWrestler));
        $this->assertFalse($bookableWrestlers->contains($this->releasedWrestler));
    }

    /**
     * @test
     */
    public function it_can_get_future_employed_wrestlers()
    {
        $futureEmployedWrestlers = Wrestler::futureEmployed()->get();

        $this->assertCount(1, $futureEmployedWrestlers);
        $this->assertTrue($futureEmployedWrestlers->contains($this->futureEmployedWrestler));
        $this->assertFalse($futureEmployedWrestlers->contains($this->bookableWrestler));
        $this->assertFalse($futureEmployedWrestlers->contains($this->injuredWrestler));
        $this->assertFalse($futureEmployedWrestlers->contains($this->suspendedWrestler));
        $this->assertFalse($futureEmployedWrestlers->contains($this->retiredWrestler));
        $this->assertFalse($futureEmployedWrestlers->contains($this->releasedWrestler));
    }

    /**
     * @test
     */
    public function it_can_get_employed_wrestlers()
    {
        $employedWrestlers = Wrestler::employed()->get();

        $this->assertCount(3, $employedWrestlers);
        $this->assertTrue($employedWrestlers->contains($this->injuredWrestler));
        $this->assertTrue($employedWrestlers->contains($this->bookableWrestler));
        $this->assertTrue($employedWrestlers->contains($this->suspendedWrestler));
        $this->assertFalse($employedWrestlers->contains($this->futureEmployedWrestler));
        $this->assertFalse($employedWrestlers->contains($this->retiredWrestler));
        $this->assertFalse($employedWrestlers->contains($this->releasedWrestler));
    }

    /**
     * @test
     */
    public function it_can_get_released_wrestlers()
    {
        $releasedWrestlers = Wrestler::released()->get();

        $this->assertCount(1, $releasedWrestlers);
        $this->assertTrue($releasedWrestlers->contains($this->releasedWrestler));
        $this->assertFalse($releasedWrestlers->contains($this->futureEmployedWrestler));
        $this->assertFalse($releasedWrestlers->contains($this->bookableWrestler));
        $this->assertFalse($releasedWrestlers->contains($this->injuredWrestler));
        $this->assertFalse($releasedWrestlers->contains($this->suspendedWrestler));
        $this->assertFalse($releasedWrestlers->contains($this->retiredWrestler));
    }

    /**
     * @test
     */
    public function it_can_get_suspended_wrestlers()
    {
        $suspendedWrestlers = Wrestler::suspended()->get();

        $this->assertCount(1, $suspendedWrestlers);
        $this->assertTrue($suspendedWrestlers->contains($this->suspendedWrestler));
        $this->assertFalse($suspendedWrestlers->contains($this->futureEmployedWrestler));
        $this->assertFalse($suspendedWrestlers->contains($this->bookableWrestler));
        $this->assertFalse($suspendedWrestlers->contains($this->injuredWrestler));
        $this->assertFalse($suspendedWrestlers->contains($this->retiredWrestler));
        $this->assertFalse($suspendedWrestlers->contains($this->releasedWrestler));
    }

    /**
     * @test
     */
    public function it_can_get_injured_wrestlers()
    {
        $injuredWrestlers = Wrestler::injured()->get();

        $this->assertCount(1, $injuredWrestlers);
        $this->assertTrue($injuredWrestlers->contains($this->injuredWrestler));
        $this->assertFalse($injuredWrestlers->contains($this->futureEmployedWrestler));
        $this->assertFalse($injuredWrestlers->contains($this->bookableWrestler));
        $this->assertFalse($injuredWrestlers->contains($this->suspendedWrestler));
        $this->assertFalse($injuredWrestlers->contains($this->retiredWrestler));
        $this->assertFalse($injuredWrestlers->contains($this->releasedWrestler));
    }

    /**
     * @test
     */
    public function it_can_get_retired_wrestlers()
    {
        $retiredWrestlers = Wrestler::retired()->get();

        $this->assertCount(1, $retiredWrestlers);
        $this->assertTrue($retiredWrestlers->contains($this->retiredWrestler));
        $this->assertFalse($retiredWrestlers->contains($this->futureEmployedWrestler));
        $this->assertFalse($retiredWrestlers->contains($this->bookableWrestler));
        $this->assertFalse($retiredWrestlers->contains($this->injuredWrestler));
        $this->assertFalse($retiredWrestlers->contains($this->suspendedWrestler));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function clearing_an_injured_wrestler_on_an_unbookable_tag_team_makes_tag_team_bookable($administrators)
    {
        $tagTeam = TagTeam::factory()->bookable()->create();
        $wrestler = $tagTeam->currentWrestlers()->first();

        $this->actAs($administrators)
            ->patch(route('wrestlers.clear-from-injury', $wrestler));

        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertTrue($wrestler->isBookable());
        });
    }
}
