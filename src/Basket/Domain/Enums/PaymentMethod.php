<?php

namespace Shop\Basket\Domain\Enums;

use Shop\Basket\Domain\Exceptions\InvalidArgumentsException;

enum PaymentMethod :int
{
    case VISA = 1;

    /**
     * @param int $paymentMethod
     * @return self
     */
    public static function in(int $paymentMethod):self
    {
        $self = self::tryFrom($paymentMethod);
        if (!$self) {
            throw new InvalidArgumentsException('Ce mode de paiement n\'est pas pris en charge par le système');
        }
        return $self;
    }
}
