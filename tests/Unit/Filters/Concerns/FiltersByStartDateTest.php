<?php

namespace Tests\Unit\Filters\Concerns;

use Illuminate\Database\Query\Builder;
use Tests\Fixtures\ExampleFilters;
use Tests\TestCase;

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
    public function models_can_be_filtered_by_their_start_date()
    {
        $this->markTestIncomplete();

        $dateSet = ['2020-01-01 00:00:00'];

        $mock = \Mockery::mock(Builder::class)
            ->shouldReceive('whereHas', \Mockery::any())
            ->shouldReceive('whereDate')
            ->withArgs(['started_at', $dateSet])
            ->once()
            ->andReturn(true)
            ->getMock();

        $builderMockFromDate = $this->subject->startedAt($dateSet);

        $this->assertSame($builderMockFromDate, $mock);
    }

    /** @test */
    public function models_can_be_filtered_by_their_start_date_range()
    {
        $this->markTestIncomplete();

        $dateSet = ['2020-01-01 00:00:00', '2020-01-04 00:00:00'];

        $mock = \Mockery::mock(Builder::class)
            ->shouldReceive('whereHas', \Mockery::any())
            ->shouldReceive('whereBetween')
            ->withArgs(['started_at', $dateSet])
            ->once()
            ->andReturn(true)
            ->getMock();

        $builderMockFromDate = $this->subject->startedAt($dateSet);

        $this->assertSame($builderMockFromDate, $mock);
    }
}
