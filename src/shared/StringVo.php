<?php

namespace Shop\shared;

class StringVo
{

    /**
     * @param string $label
     */
    public function __construct(private readonly string $label)
    {
    }

    public function value():string
    {
        return $this->label;
    }
}
