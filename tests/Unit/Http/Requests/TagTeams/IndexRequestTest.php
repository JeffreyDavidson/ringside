<?php

namespace Tests\Unit\Http\Requests\TagTeams;

use App\Enums\TagTeamStatus;
use App\Http\Requests\TagTeams\IndexRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
 * @group tagteams
 * @group roster
 */
class IndexRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @var IndexRequest */
    private $subject;

    public function setUp(): void
    {
        $this->subject = new IndexRequest();
    }

    /** @test */
    public function rules_returns_validation_requirements()
    {
        $rules = $this->subject->rules();

        $this->assertValidationRules([
            'status' => ['nullable', 'string', TagTeamStatus::rule()],
            'started_at' => ['nullable', 'array'],
        ], $rules);
    }
}
