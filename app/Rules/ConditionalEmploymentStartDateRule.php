<?php

namespace App\Rules;

use Illuminate\Support\Arr;
use Illuminatech\Validation\Composite\CompositeRule;

class ConditionalEmploymentStartDateRule extends CompositeRule
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    protected function rules($startedAt = null): array
    {
        return array_merge($startedAt ? ['string', 'date_format:Y-m-d H:i:s'] : [], [
            new EmploymentStartDateCanBeChanged($this->model),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function passes($attribute, $value): bool
    {
        $data = [];

        Arr::set($data, $attribute, $value); // ensure correct validation for array attributes like 'item_ids.*' or 'items.*.id'

        $validator = $this->getValidatorFactory()->make(
            $data,
            [
                $attribute => $this->rules($value),
            ],
            $this->messages()
        );

        if ($validator->fails()) {
            $this->message = $validator->getMessageBag()->first();

            return false;
        }

        return true;
    }

    protected function messages(): array
    {
        return [
            'string' => 'Only string is allowed.',
            'date_format' => ':attribute is too short.',
        ];
    }
}
