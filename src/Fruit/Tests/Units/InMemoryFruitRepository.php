<?php

namespace Shop\Fruit\Tests\Units;

use Shop\Fruit\Domain\Fruit;
use Shop\Fruit\Domain\FruitRepository;
use Shop\Reference\Domain\TheReference;
use Shop\shared\Id;

class InMemoryFruitRepository implements FruitRepository
{
    /**
     * @var Fruit[][]
     */
    private array $fruits = [];

    /**
     * @param Fruit $fruit
     * @return void
     */
    public function save(Fruit $fruit): void
    {
        $this->fruits[$fruit->id()->value()][$fruit->referenceId()->value()] = $fruit;
    }

    /**
     * @param TheReference $reference
     * @return Fruit[]
     */
    public function byReference(TheReference $reference): array
    {
        $fruitsByReference = [];
        foreach ($this->fruits as $id => $fruit) {
            array_key_first($fruit) !== $reference->id()->value() ? : $fruitsByReference[$id] = $fruit;
        }
        return $fruitsByReference;
    }

    /**
     * @param Fruit[] $fruits
     * @return void
     */
    public function saveMany(array $fruits ):void
    {
        foreach ( $fruits as $fruit) {
            $this->save($fruit);
        }
    }
}
