<?php

namespace Shop\Fruit\Domain;

use Shop\Reference\Domain\TheReference;
use Shop\shared\Id;

interface FruitRepository
{

    /**
     * @param TheReference $reference
     * @return Fruit[]|null
     */
    public function byReference(TheReference $reference ) : ? array;

    public function save(Fruit $fruit):void;
}
