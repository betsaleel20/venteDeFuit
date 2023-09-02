<?php

namespace Shop\Basket\Domain\ValuesObject;

use Shop\shared\Id;

readonly class BasketElement
{
    /**
     * @param Id $referenceId
     * @param Quantity $quantity
     */
    public function __construct(private Id $referenceId, private Quantity $quantity)
    {
    }


    public function referenceId(): Id
    {
        return $this->referenceId;
    }

    public function quantity():Quantity
    {
        return $this->quantity;
    }
}
