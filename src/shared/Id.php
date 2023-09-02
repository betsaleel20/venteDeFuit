<?php

namespace Shop\shared;


readonly class Id
{
    private string $id;
    public function __construct( ?string $id = null )
    {
        $this->id = is_null($id) ? uniqid() : $id;
    }

    public function value():string
    {
        return $this->id;
    }
}
