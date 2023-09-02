<?php

namespace Shop\Reference\Application\Query;


use Shop\shared\Library\PdoConnexion;

readonly class GetAllReferencesQueryHandler
{

    public function __construct(
        private PdoConnexion $dbConnexion
    )
    {
    }

    /**
     * @return GetAllReferencesResponse
     */
    public function handle(): GetAllReferencesResponse
    {

        $response = new GetAllReferencesResponse();
        $sqlRequest = "
            SELECT the_reference_label, the_reference_label
            FROM the_references
        ";

        $statement = $this->dbConnexion->getPdo()->prepare($sqlRequest);
        $statement->setFetchMode(\PDO::FETCH_CLASS, ReferenceDTO::class);
        $statement->execute();
        $response->references = $statement->fetchAll();

        return $response;
    }
}
