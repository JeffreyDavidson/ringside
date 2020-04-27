<?php

namespace Tests\Unit\Http\Requests\Managers;

use App\Enums\ManagerStatus;
use App\Http\Requests\Managers\IndexRequest;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/*
 * @group managers
 * @group roster
 */
class IndexRequestTest extends TestCase
{
    use AdditionalAssertions;

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
            'status' => ['nullable', 'string', ManagerStatus::rule()],
            'started_at' => ['nullable', 'string', 'date_format:Y-m-d H:i:s'],
        ], $rules);
    }
}
