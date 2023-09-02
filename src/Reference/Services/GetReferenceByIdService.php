<?php

namespace Shop\Reference\Services;

use Shop\Reference\Domain\Exceptions\NotFoundReferenceException;
use Shop\Reference\Domain\TheReference;
use Shop\Reference\Domain\Repository\ReferenceRepository;
use Shop\shared\Id;

readonly class GetReferenceByIdService
{
    public function __construct(
        private ReferenceRepository $repository
    )
    {
    }

    /**
     * @param Id $referenceId
     * @return TheReference
     * @throws NotFoundReferenceException
     */
    public function execute(Id $referenceId): TheReference
    {
        $foundReference = $this->repository->byId($referenceId);
        $foundReference ?? throw new NotFoundReferenceException('Cette reference n\'existe plus dans le système!');
        return $foundReference;
    }
}
