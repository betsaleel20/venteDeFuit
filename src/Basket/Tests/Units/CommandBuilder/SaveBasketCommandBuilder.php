<?php

namespace Shop\Basket\Tests\Units\CommandBuilder;

use Shop\Basket\Application\Command\Save\SaveBasketCommand;

class SaveBasketCommandBuilder
{
    private ?string $basketId;
    private int $quantity;
    private string $referenceId;

    public static function asBuilder():self
    {
        $self = new self();
        $self->referenceId = 'badReferenceId';
        $self->quantity = 1;
        $self->basketId = null;
        return $self;
    }

    public function withBasketId(string $basketId):self
    {
        $this->basketId = $basketId;
        return $this;
    }

    public function withQuantity(int $quantity):self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function withReferenceId(string $referenceId):self
    {
        $this->referenceId = $referenceId;
        return $this;
    }

    public function build(): SaveBasketCommand
    {
        return new SaveBasketCommand(
            reference: $this->referenceId,
            quantity: $this->quantity,
            basketId: $this->basketId
        );
    }
}
