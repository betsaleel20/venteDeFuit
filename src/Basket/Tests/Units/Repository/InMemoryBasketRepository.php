<?php

namespace Shop\Basket\Tests\Units\Repository;

use Shop\Basket\Domain\Basket;
use Shop\Basket\Domain\Repository\BasketRepository;
use Shop\shared\Id;

class InMemoryBasketRepository implements BasketRepository
{
    /**
     * @var Basket[]
     */
    private array $baskets;

    public function __construct()
    {
        $this->baskets = [];
    }

    public function save(Basket $basket):void
    {
        $this->baskets[$basket->id()->value()] = $basket;
    }

    /**
     * @param Id $basketId
     * @return Basket|null
     */
    public function byId(Id $basketId):?Basket
    {
        if(array_key_exists($basketId->value(), $this->baskets)){
            return $this->baskets[$basketId->value()];
        }
        return null;
    }

    /**
     * @return Basket[]
     */
    public function baskets():array
    {
        return $this->baskets;
    }
}
