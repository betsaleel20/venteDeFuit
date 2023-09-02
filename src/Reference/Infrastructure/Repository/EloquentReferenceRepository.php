<?php

namespace Shop\Reference\Infrastructure\Repository;

use Shop\Reference\Domain\TheReference as DomainReference;
use Shop\Reference\Domain\Repository\ReferenceRepository;
use Shop\Reference\Infrastructure\Model\TheReference;
use Shop\shared\Exceptions\ErrorOnSaveReferenceException;
use Shop\shared\Id;

class EloquentReferenceRepository implements ReferenceRepository
{

    public function save(DomainReference $reference): void
    {
        try {

            $eReference = TheReference::whereId($reference->id()->value())->first();

            $eReference ? $eReference->fill($reference->toArray())->save() :

                (new TheReference())->fill($reference->toArray())->save();

        } catch (\Exception $e) {
            throw new ErrorOnSaveReferenceException($e->getMessage());
        }
    }

}
