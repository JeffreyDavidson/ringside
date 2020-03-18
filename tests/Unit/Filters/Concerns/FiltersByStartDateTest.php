<?php

namespace Tests\Unit\Filters\Concerns;

use Tests\TestCase;
use Tests\Fixtures\ExampleFilters;
use Illuminate\Database\Query\Builder;

/**
 * @group filters
 */
class FiltersByStartDateTest extends TestCase
{
    /* @var Tests\Fixtures\ExampleFilters */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = app(ExampleFilters::class);
    }

    /** @test */
    public function models_can_be_filtered_by_their_start_date_daily()
    {
        $dateSet = ['2020-01-01 00:00:00'];

        $mock = \Mockery::mock(Builder::class)
            ->shouldReceive('whereHas')
            ->withArgs([
                \Mockery::any(),
                \Mockery::on(function($mock) use ($dateSet) {
                    $mock->shouldReceive('whereDate')->withArgs(['started_at', $dateSet[0]]);
                })
            ])
            ->once()
            ->andReturn(true)
            ->getMock();

        $this->subject->apply($mock);

        $builderMockFromDate = $this->subject->startedAt($dateSet);

        $this->assertSame($builderMockFromDate, $mock);
    }

    /** @test */
    public function models_can_be_filtered_by_their_start_date_range()
    {
        $dateSet = ['2020-01-01 00:00:00', '2020-01-04 00:00:00'];

        $mock = \Mockery::mock(Builder::class)
            ->shouldReceive('whereHas', \Mockery::any())
            ->shouldReceive('whereBetween')
            ->withArgs(['started_at', $dateSet])
            ->once()
            ->andReturn(true)
            ->getMock();

        $this->subject->apply($mock);

        $builderMockFromDate = $this->subject->startedAt($dateSet);

        $this->assertSame($builderMockFromDate, $mock);
    }
}
