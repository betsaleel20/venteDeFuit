<?php

namespace Shop\Fruit\Services;

use Shop\Fruit\Domain\Exceptions\NotFoundFruitException;
use Shop\Fruit\Domain\Fruit;
use Shop\Fruit\Domain\FruitRepository;
use Shop\shared\Id;

readonly class EloquentGetFruitByIdService
{
    public function __construct( private FruitRepository $repository )
    {
    }

    public function execute(Id $fruitId)
    {

    }
}
