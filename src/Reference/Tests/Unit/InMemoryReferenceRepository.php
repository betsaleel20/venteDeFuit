<?php

namespace Shop\Reference\Tests\Unit;

use Shop\Reference\Domain\TheReference;
use Shop\Reference\Domain\Repository\ReferenceRepository;
use Shop\shared\Id;

class InMemoryReferenceRepository implements ReferenceRepository
{
    /**
     * @var TheReference[]
     */
    public array $references = [];

    /**
     * @param TheReference $reference
     * @return void
     */
    public function save(TheReference $reference):void
    {
        $this->references[$reference->id()->value()] = $reference;
    }
}
