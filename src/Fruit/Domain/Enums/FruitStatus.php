<?php

namespace Shop\Fruit\Domain\Enums;

enum FruitStatus : int
{
    case AVAILABLE = 1;
    case SOLD = 2;
}
