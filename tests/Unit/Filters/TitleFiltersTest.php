<?php

namespace Tests\Unit\Filters;

use App\Filters\Concerns\FiltersByStatus;
use App\Filters\TitleFilters;
use Illuminate\Database\Query\Builder;
use Tests\TestCase;

/*
 * @group titles
 * @group filters
 */
class TitleFiltersTest extends TestCase
{
    /** @var App\Filters\TitleFilters */
    protected $subject;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = app(TitleFilters::class);
    }

    /** @test */
    public function title_filters_include_filtering_by_status()
    {
        $this->assertUsesTrait(FiltersByStatus::class, $this->subject);
        $this->assertTrue(in_array('status', $this->subject->filters));
    }

    /** @test */
    public function title_filters_include_filtering_by_date()
    {
        $this->assertTrue(in_array('introducedAt', $this->subject->filters));
    }

    /** @test */
    public function title_filters_can_be_filtered_with_one_date()
    {
        $dateSet = ['2020-01-01 00:00:00'];

        $mock = \Mockery::mock(Builder::class)
            ->shouldReceive('whereDate')
            ->withArgs(['introduced_at', $dateSet[0]])
            ->once()
            ->andReturn(true)
            ->getMock();

        $this->subject->apply($mock);

        $builderMockFromDate = $this->subject->introducedAt($dateSet);

        $this->assertSame($builderMockFromDate, $mock);
    }

    /** @test */
    public function title_filters_can_be_filtered_with_a_date_range()
    {
        $dateSet = ['2020-01-01 00:00:00', '2020-01-03 00:00:00'];

        $mock = \Mockery::mock(Builder::class)
            ->shouldReceive('whereBetween')
            ->withArgs(['introduced_at', $dateSet])
            ->once()
            ->andReturn(true)
            ->getMock();

        $this->subject->apply($mock);

        $builderMockFromDate = $this->subject->introducedAt($dateSet);

        $this->assertSame($builderMockFromDate, $mock);
    }
}
