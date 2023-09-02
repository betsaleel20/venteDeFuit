<?php

namespace Shop\Reference\Domain\Service;

use Shop\Reference\Domain\TheReference;
use Shop\shared\Id;

interface GetReferenceByIdService
{
    /**
     * @param Id $referenceId
     * @return TheReference
     */
    public function execute(Id $referenceId): TheReference;

}
