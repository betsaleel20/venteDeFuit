<?php

namespace Shop\Fruit\Infrastructure\database\Factory;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shop\Fruit\Infrastructure\Model\Fruit;

class FruitFactory extends Factory
{

    protected $model = Fruit::class;
    public function definition():array
    {
        return [
            'id' => $this->faker->uuid,
            'fruit_name' => $this->faker->name,
            'product_reference_id' => $this->faker->uuid,
        ];
    }
}
