<?php

namespace Shop\Reference\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Shop\Reference\Domain\Repository\ReferenceRepository;
use Shop\Reference\Domain\Service\GetReferenceByIdService;
use Shop\Reference\Domain\TheReference as DomainReference;
use Shop\Reference\Infrastructure\Repository\EloquentReferenceRepository;
use Shop\Reference\Services\PdoGetReferenceByIdService;
use Shop\shared\Infrastructure\Database\EloquentPdoConnexion;
use Shop\shared\Library\PdoConnexion;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;
use Tests\TestCase;

class EloquentReferenceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private PdoConnexion $pdoConnexion;
    private ReferenceRepository $repository;
    private GetReferenceByIdService $getReferenceByIdServiceOrThrowNotFoundException;

    public function setUp(): void
    {
        parent::setUp();
        $this->pdoConnexion = new EloquentPdoConnexion();
        $this->repository = new EloquentReferenceRepository();
        $this->getReferenceByIdServiceOrThrowNotFoundException = new PdoGetReferenceByIdService($this->pdoConnexion);
    }

    public function test_can_create_reference()
    {
        $reference = DomainReference::create(
            referenceName: new StringVo('My product reference Label'),
            referencePrice: new PriceVo(500)
        );
        $this->repository->save($reference);
        $eReference = $this->getReferenceByIdServiceOrThrowNotFoundException->execute($reference->id()->value());

        $this->assertNotNull($eReference);
        $this->assertEquals($reference->id()->value(),$eReference->id()->value());
    }

    /**
     * @throws \Exception
     */
    public function test_can_update_reference()
    {
        $initReference = $this->buildSUT();
        $referenceToSave = DomainReference::create(
            referenceName: new StringVo('new Label'),
            referencePrice: new PriceVo(4500),
            referenceId:$initReference->id(),
        );
        $this->repository->save($referenceToSave);
        $eReference = $this->getReferenceByIdServiceOrThrowNotFoundException->execute($referenceToSave->id()->value());

        $this->assertEquals($referenceToSave->id()->value(), $eReference->id()->value());
        $this->assertEquals($referenceToSave->referenceName()->value(), $eReference->referenceName()->value());
        $this->assertEquals($referenceToSave->referencePrice()->value(), $eReference->referencePrice()->value());
    }

    private function buildSUT(): DomainReference
    {
        $reference = DomainReference::create(
            referenceName: new StringVo('My TheReference Label'),
            referencePrice: new PriceVo(50)
        );

        $this->repository->save($reference);
        return $reference;
    }


}
