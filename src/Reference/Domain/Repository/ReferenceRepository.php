<?php

namespace Shop\Reference\Domain\Repository;

use Shop\Reference\Domain\TheReference;
use Shop\shared\Id;

interface ReferenceRepository
{
    public function save(TheReference $reference):void;

}
