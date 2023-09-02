<?php

namespace Shop\Basket\Application\Command\Save;

use Shop\Basket\Domain\Exceptions\InvalidCommandException;

class SaveBasketCommand
{

    public string $fruitRef;
    public ?int $quantity;
    public ?string $basketId;

    /**
     * @param string $reference
     * @param int|null $quantity
     * @param string|null $basketId
     */
    public function __construct(string $reference, ?int $quantity = null, ?string $basketId = null)
    {
        $this->fruitRef = $reference;
        $this->quantity = $quantity;
        $this->basketId = $basketId;
        $this->validate();
    }

    /**
     * @return void
     * @throws InvalidCommandException
     */
    private function validate():void
    {
        if($this->quantity && $this->quantity <= 0 ){
            throw new InvalidCommandException('Veuillez entrer une quantité supérieure à zéro !');
        }
        !empty($this->fruitRef) ? : throw new InvalidCommandException('Veuillez entrer un reference valide !');

    }
}
