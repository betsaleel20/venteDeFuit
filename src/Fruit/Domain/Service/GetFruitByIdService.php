<?php

namespace Shop\Fruit\Domain\Service;

use Shop\Fruit\Domain\Fruit;
use Shop\shared\Id;

interface GetFruitByIdService
{
    public function execute(Id $fruitId): Fruit;
}
