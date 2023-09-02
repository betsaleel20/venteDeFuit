<?php

namespace Shop\shared;

class PriceVo
{

    /**
     * @param float $price
     */
    public function __construct(private readonly float $price)
    {
    }

    public function value():float
    {
        return $this->price;
    }
}
