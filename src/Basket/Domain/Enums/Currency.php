<?php

namespace Shop\Basket\Domain\Enums;

use Shop\Basket\Domain\Exceptions\InvalidArgumentsException;

enum Currency : int
{
    case DOLLAR = 1;

    /**
     * @param int $currency
     * @return Currency
     */
    public static function in(int $currency):self
    {
        $self = self::tryFrom($currency);
        if (!$self) {
            throw new InvalidArgumentsException('Ce mode de paiement n\'est pas pris en charge par le système');
        }
        return $self;
    }
}
