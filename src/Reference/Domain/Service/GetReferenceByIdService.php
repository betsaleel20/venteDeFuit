<?php

namespace Shop\Reference\Domain\Service;

use Shop\Reference\Domain\TheReference;
use Shop\shared\Id;

interface GetReferenceByIdService
{
    /**
     * @param string $referenceId
     * @return TheReference
     */
    public function execute(string $referenceId): TheReference;

}
