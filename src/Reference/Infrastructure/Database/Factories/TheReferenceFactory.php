<?php

namespace Shop\Reference\Infrastructure\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shop\Reference\Infrastructure\Model\TheReference;

class TheReferenceFactory extends Factory
{
    protected $model = TheReference::class;

    public function definition(): array
    {
        return  [
            'id' => $this->faker->uuid,
            'the_reference_label' => $this->faker->name,
            'the_reference_price' => $this->faker->randomFloat(nbMaxDecimals: 2, min: 10, max: 200),
        ];
    }
}
