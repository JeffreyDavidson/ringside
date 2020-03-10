<?php

namespace Tests\Unit\Filters;

use App\Filters\Concerns\FiltersByStatus;
use App\Filters\EventFilters;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Tests\TestCase;

class EventFiltersTest extends TestCase
{
    /** @var App\Filters\EventFilters */
    protected $subject;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = app(EventFilters::class);
    }

    /** @test */
    public function event_filters_include_filtering_by_status()
    {
        $this->assertUsesTrait(FiltersByStatus::class, $this->subject);
        $this->assertTrue(in_array('status', $this->subject->filters));
    }

    /** @test */
    public function event_filters_include_filtering_by_date()
    {
        $now = now();
        Carbon::setTestNow($now);

        $dateSet = [$now, $now->addDays(2)];

        $this->assertTrue(in_array('date', $this->subject->filters));

        $builderMock = $this->getBuilderMock(true, $dateSet);
        $this->subject->apply($builderMock);

        $builderMockFromDate = $this->subject->date(
            $dateSet
        );

        $this->assertInstanceOf(Builder::class, $builderMock);
        $this->assertSame($builderMockFromDate, $builderMock);
    }


    private function getBuilderMock($shouldCallWhereBetween, $dateSet)
    {
        $mock = \Mockery::mock(Builder::class);

        // Make sure we expect strings, not objects
        foreach ($dateSet as $arrIndex => $date) {
            $dateSet[$arrIndex] = Carbon::parse($date);
        }

        if ($shouldCallWhereBetween) {
            $mock->shouldReceive('whereBetween')
                // ->withArgs($dateSet)
                ->once()
                ->andReturn(true);
        } else {
            $mock->shouldReceive('whereDate')
                ->andReturn(true);
        }

        return $mock;
    }
}
