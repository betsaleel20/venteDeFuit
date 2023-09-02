<?php

namespace Shop\Basket\Tests\Units\Service;


use Shop\Basket\Domain\Exceptions\NotFoundBasketElementException;
use Shop\Basket\Domain\Repository\GetBasketElementByIdService;
use Shop\Basket\Domain\ValuesObject\BasketElement;

class InMemoryGetBasketElementByIdServiceOrThrowNorFoundException implements GetBasketElementByIdService
{

    /**
     * @var BasketElement[]
     */
    public array $basketElements = [];

    /**
     * @throws NotFoundBasketElementException
     */
    public function execute(string $basketElementId): BasketElement
    {
        $key = array_key_exists($basketElementId,$this->basketElements);
        if(!$key){
            throw new NotFoundBasketElementException('Cet element n\'existe pas dans votre panier');
        }
        return $this->basketElements[$basketElementId];
    }
}
