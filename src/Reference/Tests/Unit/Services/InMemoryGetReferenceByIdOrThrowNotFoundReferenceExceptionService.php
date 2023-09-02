<?php

namespace Shop\Reference\Tests\Unit\Services;

use Shop\Reference\Domain\Exceptions\NotFoundReferenceException;
use Shop\Reference\Domain\Service\GetReferenceByIdService;
use Shop\Reference\Domain\TheReference;
use Shop\shared\Id;

class InMemoryGetReferenceByIdOrThrowNotFoundReferenceExceptionService implements GetReferenceByIdService
{

    /**
     * @var TheReference[]
     */
    public array $references = [];
    public function execute(string $referenceId): TheReference
    {
        if(array_key_exists($referenceId, $this->references)) {
            return $this->references[$referenceId];
        }
        throw new NotFoundReferenceException('Cette reference n\'existe pas');
    }
}
