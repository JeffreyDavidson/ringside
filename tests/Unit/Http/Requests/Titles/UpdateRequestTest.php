<?php

namespace Tests\Unit\Http\Requests\Titles;

use App\Http\Requests\Titles\UpdateRequest;
use Illuminate\Validation\Rules\Unique;
use Tests\TestCase;

/**
 * @group titles
 * @group requests
 */
class UpdateRequestTest extends TestCase
{
    /** @test */
    public function rules_returns_validation_requirements()
    {
        $subject = $this->createFormRequest(UpdateRequest::class);
        $rules = $subject->rules();

        $this->assertValidationRules(
            [
                'name' => ['required', 'min:3', 'ends_with:Title,Titles'],
                'started_at' => ['nullable', 'string', 'date_format:Y-m-d H:i:s'],
            ],
            $rules
        );

        $this->assertValidationRuleContains($rules['name'], Unique::class);
        // $this->assertValidationRuleContains($rules['activated_at'], ActivationStartDateCanBeChanged::class);
    }
}
