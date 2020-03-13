<?php

namespace Tests\Unit\Filters\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Tests\TestCase;

/*
 * @group filters
 */
class FilterByStatusTest extends TestCase
{
    /** @var Example */
    protected $subject;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new Example();
    }

    /** @test */
    public function models_can_be_filtered_by_their_start_date()
    {
        $this->markTestIncomplete();

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

class Example
{

}
