<?php

namespace Shop\Basket\Infrastructure\database\Factory;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shop\Basket\Domain\Enums\BasketStatus;
use Shop\Basket\Infrastructure\Basket;

class BasketFactory extends Factory
{

    protected $model = Basket::class;

    public function definition():array
    {
        return [
            'id' => $this->faker->uuid,
            'status' => BasketStatus::IS_SAVED->value,
        ];
    }
}
