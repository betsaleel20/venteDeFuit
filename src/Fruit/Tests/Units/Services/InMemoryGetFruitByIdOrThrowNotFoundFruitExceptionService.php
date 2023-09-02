<?php

namespace Shop\Fruit\Tests\Units\Services;

use Shop\Fruit\Domain\Exceptions\NotFoundFruitException;
use Shop\Fruit\Domain\Fruit;
use Shop\Fruit\Domain\Service\GetFruitByIdService;
use Shop\shared\Id;

class InMemoryGetFruitByIdOrThrowNotFoundFruitExceptionService implements GetFruitByIdService
{
    /**
     * @var Fruit[][]
     */
    public array $fruits = [];

    /**
     * @param Id $fruitId
     * @return Fruit
     */
    public function execute(Id $fruitId): Fruit
    {
        if(array_key_exists($fruitId->value(), $this->fruits)){
            $fruitInArray = $this->fruits[$fruitId->value()];
            $fruitInArray = $fruitInArray[array_key_first($fruitInArray)];
        }
        $fruitInArray ?? throw new NotFoundFruitException("Ce fruit n'existe plus !");

        return $fruitInArray;
    }
}
