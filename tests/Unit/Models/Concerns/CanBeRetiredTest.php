<?php

namespace Tests\Unit\Models\Concerns;

use App\Exceptions\CannotBeRetiredException;
use App\Exceptions\CannotBeUnretiredException;
use App\Models\Manager;
use App\Models\Referee;
use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group traits
 */
class CanBeRetiredTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_with_a_retirement_is_retired($modelClass)
    {
        $model = factory($modelClass)->states('retired')->create();

        $this->assertTrue($model->isRetired());
    }

    /**
     * @test
     * @dataProvider modelClassDataProvider
     */
    public function a_bookable_single_roster_member_can_be_retired($modelClass)
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $model = factory($modelClass)->states('bookable')->create();

        $model->retire();

        $this->assertEquals('retired', $model->status);
        $this->assertCount(1, $model->retirements);
        $this->assertEquals($now->toDateTimeString(), $model->currentRetirement->started_at);
    }

    /**
     * @test
     * @dataProvider modelClassDataProvider
     */
    public function a_suspended_single_roster_member_can_be_retired($modelClass)
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $model = factory($modelClass)->states('suspended')->create();

        $this->assertNull($model->currentSuspension->ended_at);

        $model->retire();

        $this->assertEquals('retired', $model->status);
        $this->assertCount(1, $model->retirements);
        $this->assertNotNull($model->previousSuspension->ended_at);
        $this->assertEquals($now->toDateTimeString(), $model->currentRetirement->started_at);
    }

    /**
     * @test
     * @dataProvider modelClassDataProvider
     */
    public function an_injured_single_roster_member_can_be_retired($modelClass)
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $model = factory($modelClass)->states('injured')->create();

        $this->assertNull($model->injuries()->latest()->first()->ended_at);

        $model->retire();

        $this->assertEquals('retired', $model->status);
        $this->assertCount(1, $model->retirements);
        $this->assertNotNull($model->previousInjury->ended_at);
        $this->assertEquals($now->toDateTimeString(), $model->currentRetirement->started_at);
    }

    /**
     * @test
     * @dataProvider modelClassDataProvider
     */
    public function a_future_employment_single_roster_member_cannot_be_retired($modelClass)
    {
        $this->expectException(CannotBeRetiredException::class);

        $model = factory($modelClass)->states('future-employment')->create();

        $model->retire();
    }

    /**
     * @test
     * @dataProvider modelClassDataProvider
     */
    public function a_retired_single_roster_member_cannot_be_retired($modelClass)
    {
        $this->expectException(CannotBeRetiredException::class);

        $model = factory($modelClass)->states('retired')->create();

        $model->retire();
    }

    /**
     * @test
     * @dataProvider modelClassDataProvider
     */
    public function a_retired_single_roster_member_can_be_unretired($modelClass)
    {
        $model = factory($modelClass)->states('retired')->create();

        $model->unretire();

        $this->assertEquals('bookable', $model->status);
        $this->assertNotNull($model->previousRetirement->ended_at);
    }

    /**
     * @test
     * @dataProvider modelClassDataProvider
     */
    public function a_future_employment_single_roster_member_cannot_be_unretired($modelClass)
    {
        $this->expectException(CannotBeUnretiredException::class);

        $model = factory($modelClass)->states('future-employment')->create();

        $model->unretire();
    }

    /**
     * @test
     * @dataProvider modelClassDataProvider
     */
    public function a_suspended_single_roster_member_cannot_be_unretired($modelClass)
    {
        $this->expectException(CannotBeUnretiredException::class);

        $model = factory($modelClass)->states('suspended')->create();

        $model->unretire();
    }

    /**
     * @test
     * @dataProvider modelClassDataProvider
     */
    public function an_injured_single_roster_member_cannot_be_unretired($modelClass)
    {
        $this->expectException(CannotBeUnretiredException::class);

        $model = factory($modelClass)->states('suspended')->create();

        $model->unretire();
    }

    /**
     * @test
     * @dataProvider modelClassDataProvider
     */
    public function a_bookable_single_roster_member_cannot_be_unretired($modelClass)
    {
        $this->expectException(CannotBeUnretiredException::class);

        $model = factory($modelClass)->states('bookable')->create();

        $model->unretire();
    }

    /**
     * @test
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_that_retires_and_unretires_has_a_previous_retirement($modelClass)
    {
        $model = factory($modelClass)->states('bookable')->create();
        $model->retire();
        $model->unretire();

        $this->assertCount(1, $model->previousRetirements);
    }

    public function modelClassDataProvider()
    {
        return [[Manager::class], [Wrestler::class], [Referee::class]];
    }
}
