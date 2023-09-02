<?php

namespace Shop\Fruit\Infrastructure\Repository;

use Shop\Fruit\Domain\Fruit as DomainFruit;
use Shop\Fruit\Domain\FruitRepository;
use Shop\Fruit\Infrastructure\Model\Fruit;
use Shop\Reference\Domain\TheReference;
use Shop\shared\Id;

class EloquentFruitRepository implements FruitRepository
{

    /**
     * @param TheReference $reference
     * @return DomainFruit[]|null
     */
    public function byReference(TheReference $reference): ?array
    {
        $fruits = Fruit::whereProductReferenceId($reference->id()->value())
            ->get()
            ->toArray();

        $domainFruits = array_map(fn(array $fruit)=> $fruit->toDomain(), $fruits );
        dd($domainFruits);
        return $domainFruits;
    }

    public function save(DomainFruit $fruit): void
    {
        $gottenFruit = Fruit::whereId($fruit->id()->value())->first();

        $gottenFruit ? $gottenFruit->fill($fruit->toArray())->save() :
        (new Fruit())->fill($fruit->toArray())->save();
    }

    public function saveMany(array $fruits): void
    {
        // TODO: Implement saveMany() method.
    }

    public function byId(Id $fruitId): ?DomainFruit
    {
        return Fruit::whereId($fruitId->value())->first()?->toDomain();
    }
}
