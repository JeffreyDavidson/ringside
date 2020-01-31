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
            'started_at.0' => ['nullable', 'string', 'date_format:Y-m-d H:i:s'],
            'started_at.1' => [
                'nullable',
                'required_with:started_at.0',
                'string',
                'date_format:Y-m-d H:i:s',
                'after:started_at.0',
            ],
        ], $rules);
    }
}
