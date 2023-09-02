<?php

namespace Shop\Basket\Domain\Enums;

use Shop\Basket\Domain\Exceptions\InvalidBasketStatusException;

enum BasketStatus :int
{
    case IS_SAVED = 1;
    case IS_DESTROYED = 2;

    public static function in(mixed $status): BasketStatus
    {
        $self = self::tryFrom($status);
        if(!$self){
            throw new InvalidBasketStatusException('Status du panier invalide!');
        }
        return $self;
    }
}
