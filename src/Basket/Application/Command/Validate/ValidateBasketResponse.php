<?php

namespace Shop\Basket\Application\Command\Validate;

class ValidateBasketResponse
{

    public bool $isValidated = false;
    public ?string $orderId = null;
}
