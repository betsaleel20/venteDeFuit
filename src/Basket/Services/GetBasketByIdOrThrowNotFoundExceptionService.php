<?php

namespace Shop\Basket\Services;

use Shop\Basket\Domain\Basket;
use Shop\Basket\Domain\Exceptions\NotFoundBasketException;
use Shop\Basket\Domain\Repository\BasketRepository;
use Shop\shared\Id;

class GetBasketByIdOrThrowNotFoundExceptionService
{

    public function __construct(
        private BasketRepository $repository
    )
    {
    }

    public function execute(Id $basketId): Basket
    {
        $basket = $this->repository->byId($basketId);
        if(!$basket){
            throw new NotFoundBasketException('Le panier que vous rcherchez n\'existe pas');
        }
        return $basket;
    }
}
