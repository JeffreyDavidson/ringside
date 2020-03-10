<?php

namespace Tests\Unit\Filters;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Tests\TestCase;

/*
 * @group events
 */
class FiltersByStatusTest extends TestCase
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
    public function models_can_be_filtered_by_their_start_date()
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

class Testing {
    public function startedAt($startedAt) {

    }
}
