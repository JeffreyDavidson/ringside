<?php

namespace Tests\Unit\Http\Requests\Wrestlers;

use App\Http\Requests\Wrestlers\UpdateRequest;
use App\Models\Wrestler;
use App\Rules\EmploymentStartDateCanBeChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group roster
 * @group requests
 */
class UpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function rules_returns_validation_requirements()
    {
        $subject = $this->createFormRequest(UpdateRequest::class);

        $wrestlerMock = $this->mock(Wrestler::class)->expects()->set('id', 1);

        $requestMock = $this->mock(Request::class)
            ->makePartial()
            ->shouldReceive('route')
            ->set('wrestler', $wrestlerMock)
            ->once()
            ->andReturn(\Mockery::self());

        $this->mock(Rule::class, function ($mock) use ($wrestlerMock, $requestMock) {
            $mock->expects()->unique('wrestler')->andReturns(\Mockery::self());
            $mock->expects()->ignore($requestMock)->andReturns(\Mockery::self());
        });

        $rules = $subject->rules();

        $this->assertValidationRules(
            [
                'name' => ['required', 'string', 'min:3'],
                'feet' => ['required', 'integer'],
                'inches' => ['required', 'integer', 'max:11'],
                'weight' => ['required', 'integer'],
                'hometown' => ['required', 'string'],
                'signature_move' => ['nullable', 'string'],
                'started_at' => ['nullable', 'string', 'date'],
            ],
            $rules
        );

        $this->assertValidationRuleContains($rules['name'], Unique::class);
        $this->assertValidationRuleContains($rules['started_at'], EmploymentStartDateCanBeChanged::class);
    }
}
