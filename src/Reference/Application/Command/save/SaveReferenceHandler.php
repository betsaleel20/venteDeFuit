<?php

namespace Shop\Reference\Application\Command\save;

use Shop\Reference\Domain\Exceptions\NotFoundReferenceException;
use Shop\Reference\Domain\Service\GetReferenceByIdService;
use Shop\Reference\Domain\TheReference;
use Shop\Reference\Domain\Repository\ReferenceRepository;
use Shop\shared\Id;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;

readonly class SaveReferenceHandler
{
    public function __construct(
        private GetReferenceByIdService $getReferenceByIdOrThrowNotFoundReferenceException,
        private ReferenceRepository     $repository
    )
    {
    }

    /**
     * @param SaveReferenceCommand $command
     * @return SaveReferenceResponse
     * @throws NotFoundReferenceException
     */
    public function handle(SaveReferenceCommand $command): SaveReferenceResponse
    {
        $response = new SaveReferenceResponse();

        $referenceId = $command->referenceId ? new Id($command->referenceId) : null;
        $foundReference = is_null($referenceId) ?
            null :
            $this->getReferenceByIdOrThrowNotFoundReferenceException->execute($referenceId->value());
        $label = new StringVo($command->label);
        $price = new PriceVo($command->price);

        $reference = TheReference::create(
            referenceName: $label,
            referencePrice: $price,
            referenceId: $foundReference?->id()
        );
        $this->repository->save($reference);

        $response->isSaved = true;
        $response->referenceId = $reference->id()->value();
        return $response;
    }
}
