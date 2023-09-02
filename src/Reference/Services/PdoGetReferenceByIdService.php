<?php

namespace Shop\Reference\Services;

use Shop\Reference\Application\Query\ReferenceDTO;
use Shop\Reference\Domain\Exceptions\NotFoundReferenceException;
use Shop\Reference\Domain\Service\GetReferenceByIdService;
use Shop\Reference\Domain\TheReference;
use Shop\Reference\Infrastructure\Model\TheReference as TheReferenceModel;
use Shop\shared\DateVo;
use Shop\shared\Id;
use Shop\shared\Library\PdoConnexion;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;

readonly class PdoGetReferenceByIdService implements GetReferenceByIdService
{
    public function __construct(
        private PdoConnexion $pdoConnexion
    )
    {
    }

    /**
     * @param string $referenceId
     * @return TheReference
     */
    public function execute(string $referenceId): TheReference
    {
        $sqlRequest = "
            SELECT
                id,
                the_reference_label AS theReferenceLabel,
                the_reference_price AS theReferencePrice,
                updated_at as updatedAt
            FROM the_references
            WHERE id = :referenceId";

        $statement = $this->pdoConnexion->getPdo()->prepare($sqlRequest);
        $statement->bindParam('referenceId', $referenceId);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute();

        $foundReference = $statement->fetchAll();
        $foundReference ?
            $foundReference = $this->asDomainReference($foundReference)
            :
            throw new NotFoundReferenceException('Cette reference n\'existe plus dans le système!');

        return $foundReference;
    }

    private function asDomainReference(array $foundReference): TheReference
    {
        $reference = new TheReference(
            id: new Id($foundReference[0]['id']),
            referenceName: new StringVo($foundReference[0]['theReferenceLabel']),
            referencePrice: new PriceVo($foundReference[0]['theReferencePrice'])
        );
        $reference->changeUpdatedAt(new DateVo($foundReference[0]['updatedAt']));
        return $reference;
    }
}
