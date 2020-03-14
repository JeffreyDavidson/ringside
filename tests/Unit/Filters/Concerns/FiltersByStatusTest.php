<?php

namespace Tests\Unit\Filters\Concerns;

use Illuminate\Database\Query\Builder;
use Tests\Fixtures\ExampleFilters;
use Tests\TestCase;

/**
 * @group filters
 */
class FiltersByStatusTest extends TestCase
{
    /** @var Tests\Fixtures\ExampleFilters */
    protected $subject;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = app(ExampleFilters::class);
    }

    /** @test */
    public function models_can_be_filtered_by_their_status()
    {
        $this->markTestIncomplete();

        $status = 'Testing';

        $this->assertTrue(in_array('date', $this->subject->filters));

        $mock = \Mockery::mock(Builder::class)
            ->shouldReceive('whereHas', \Mockery::any())
            ->shouldReceive('whereDate')
            ->withArgs(['started_at', $dateSet])
            ->once()
            ->andReturn(true)
            ->getMock();

        $builderMockFromDate = $this->subject->status($status);

        $this->assertInstanceOf(Builder::class, $builderMock);
        $this->assertSame($builderMockFromDate, $builderMock);
    }
}
