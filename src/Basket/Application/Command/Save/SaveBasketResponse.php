<?php

namespace Shop\Basket\Application\Command\Save;

class SaveBasketResponse
{

    public bool $isSaved = false;
    public ?string $basketId = null;
}
