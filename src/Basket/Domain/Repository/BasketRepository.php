<?php

namespace Shop\Basket\Domain\Repository;


use Shop\Basket\Domain\Basket;
use Shop\shared\Id;

interface BasketRepository
{
    /**
     * @param Basket $basket
     * @return void
     */
    public function save(Basket $basket): void;

    /**
     * @param Id $basketId
     * @return Basket|null
     */
    public function byId(Id $basketId): ?Basket;
}
