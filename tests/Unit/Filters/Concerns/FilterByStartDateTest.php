<?php

namespace Tests\Unit\Filters\Concerns;

use Illuminate\Database\Query\Builder;
use Tests\TestCase;

/*
 *
 * @group filters
 */
class FilterByStartDateTest extends TestCase
{
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new Example();
    }

    /** @test */
    public function models_can_be_filtered_by_their_start_date()
    {
        $this->markTestIncomplete();

        $dateSet = ['2020-01-01 00:00:00'];

        $mock = \Mockery::mock(Builder::class)
            ->shouldReceive('whereBetween')
            ->withArgs(['started_at', $dateSet])
            ->once()
            ->andReturn(true)
            ->getMock();

        $builderMockFromDate = $this->subject->date($dateSet);

        $this->assertSame($builderMockFromDate, $mock);
    }
}

class Example
{
}
