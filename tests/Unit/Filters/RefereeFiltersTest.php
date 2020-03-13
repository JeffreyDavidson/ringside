<?php

namespace Tests\Unit\Filters;

use App\Filters\Concerns\FiltersByStartDate;
use App\Filters\Concerns\FiltersByStatus;
use App\Filters\RefereeFilters;
use Tests\TestCase;

/*
 * @group referees
 * @group roster
 * @group filters
 */
class RefereeFiltersTest extends TestCase
{
    /** @var App\Filters\RefereeFilters */
    protected $subject;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = app(RefereeFilters::class);
    }

    /** @test */
    public function referee_filters_include_filtering_by_status()
    {
        $this->assertUsesTrait(FiltersByStatus::class, $this->subject);
        $this->assertTrue(in_array('status', $this->subject->filters));
    }

    /** @test */
    public function referee_filters_include_filtering_by_started_at_date()
    {
        $this->assertUsesTrait(FiltersByStartDate::class, $this->subject);
        $this->assertTrue(in_array('startedAt', $this->subject->filters));
    }
}
