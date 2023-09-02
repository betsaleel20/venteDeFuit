<?php

namespace Shop\Basket\Application\Command\Validate;

use Shop\Basket\Domain\Exceptions\InvalidCommandException;

class ValidateBasketCommand
{

    public string $basketId;
    public int $paymentMethod;
    public int $currency;
    public function __construct(
        string $basketId,
        int $paymentMethod,
        int $currency
    )
    {
        $this->validate($basketId, $paymentMethod, $currency);
    }

    private function validate($basketId, $paymentMethod, $currency): void
    {
        $this->validateArgumentType($basketId, 'string', 'basketId');
        $this->validateArgumentType($paymentMethod, 'integer','Methode de paiment');
        $this->validateArgumentType($currency, 'integer','Monnaie utilisé');

        if (empty($basketId) ||
            is_null($paymentMethod) ||
            is_null($currency)
        ) {
            throw new InvalidCommandException('Les données que vous avez entrés sont invalides');
        }

        $this->basketId = $basketId;
        $this->paymentMethod = $paymentMethod;
        $this->currency = $currency;
    }

    private function validateArgumentType($variable, string $expectedType, string $fieldName):void
    {
        $type = gettype($variable);
        if($type !== $expectedType){
            throw new InvalidCommandException("Vous devez entrer des donnees de type <".$expectedType."> \n
            pour le champ <".$fieldName.">");

        }
    }
}
