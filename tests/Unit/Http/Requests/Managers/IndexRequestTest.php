<?php

namespace Tests\Unit\Http\Requests\Managers;

use App\Http\Requests\Managers\IndexRequest;
use JMac\Testing\Traits\HttpTestAssertions;
use Tests\TestCase;

/*
 * @group managers
 * @group roster
 */
class IndexRequestTest extends TestCase
{
    use HttpTestAssertions;

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
            'first_name' => ['required', 'string', 'min:3'],
            'last_name' => ['required', 'string', 'min:3'],
            'started_at' => ['nullable', 'string', 'date_format:Y-m-d H:i:s'],
        ], $rules);
    }
}
