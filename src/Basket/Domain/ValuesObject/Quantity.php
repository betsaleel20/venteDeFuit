<?php

namespace Shop\Basket\Domain\ValuesObject;

use Shop\Basket\Domain\Exceptions\InvalidCommandException;

readonly class Quantity
{
    /**
     * @param int $quantity
     */
    public function __construct(private int $quantity)
    {
        $this->validate();
    }

    /**
     * @return int
     */
    public function value():int
    {
        return $this->quantity;
    }

    /**
     * @return void
     */
    private function validate():void
    {
        $this->quantity >= 0 ? : throw new InvalidCommandException('La quantité doit etre supérieure à zéro');
    }
}
