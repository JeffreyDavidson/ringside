<?php

namespace Tests\Feature\Generic\Wrestlers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group generics
 * @group roster
 */
class CreateWrestlerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valid parameters for request.
     *
     * @param  array $overrides
     * @return array
     */
    private function validParams($overrides = [])
    {
        return array_replace([
            'name' => 'Example Wrestler Name',
            'feet' => '6',
            'inches' => '4',
            'weight' => '240',
            'hometown' => 'Laraville, FL',
            'signature_move' => 'The Finisher',
            'started_at' => now()->toDateTimeString(),
        ], $overrides);
    }

    /** @test */
    public function a_wrestler_started_today_or_before_is_bookable()
    {
        $this->actAs('administrator');

        $this->storeRequest('wrestler', $this->validParams(['started_at' => now()->toDateTimeString()]));

        tap(Wrestler::first(), function ($wrestler) {
            $this->assertEquals('bookable', $wrestler->status);
        });
    }

    /** @test */
    public function a_wrestler_started_after_today_is_pending_employment()
    {
        $this->actAs('administrator');

        $this->storeRequest('wrestler', $this->validParams(['started_at' => Carbon::tomorrow()->toDateTimeString()]));

        tap(Wrestler::first(), function ($wrestler) {
            $this->assertEquals('pending-employment', $wrestler->status);
        });
    }

    /** @test */
    public function a_wrestler_signature_move_is_optional()
    {
        $this->actAs('administrator');

        $response = $this->storeRequest('wrestler', $this->validParams(['signature_move' => null]));

        $response->assertSessionDoesntHaveErrors('signature_move');
    }

    /** @test */
    public function a_wrestler_started_at_date_is_optional()
    {
        $this->actAs('administrator');

        $response = $this->storeRequest('wrestler', $this->validParams(['started_at' => null]));

        $response->assertSessionDoesntHaveErrors('started_at');
    }

    /** @test */
    public function a_wrestler_can_be_created()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs('administrator');

        $response = $this->storeRequest('wrestler', $this->validParams());

        $response->assertRedirect(route('wrestlers.index'));
        tap(Wrestler::first(), function ($wrestler) use ($now) {
            $this->assertEquals('Example Wrestler Name', $wrestler->name);
            $this->assertEquals(76, $wrestler->height);
            $this->assertEquals(240, $wrestler->weight);
            $this->assertEquals('Laraville, FL', $wrestler->hometown);
            $this->assertEquals('The Finisher', $wrestler->signature_move);
        });
    }
}
