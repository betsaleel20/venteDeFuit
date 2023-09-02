<?php

namespace Shop\Reference\Tests\Feature;

use Shop\Reference\Domain\Exceptions\NotFoundReferenceException;
use Shop\Reference\Infrastructure\Model\TheReference;
use Shop\Reference\Services\PdoGetReferenceByIdService;
use Shop\shared\Infrastructure\Database\EloquentPdoConnexion;
use Shop\shared\Library\PdoConnexion;
use Tests\TestCase;

class GetReferenceByIdServiceTest extends TestCase
{
    private PdoGetReferenceByIdService $getReferenceByIdServiceOrThrowNotFoundException;
    private PdoConnexion $pdoConnexion;
    public function setUp():void
    {
        parent::setUp();
        $this->pdoConnexion = new EloquentPdoConnexion();
        $this->getReferenceByIdServiceOrThrowNotFoundException = new PdoGetReferenceByIdService($this->pdoConnexion);
    }

    public function test_can_get_reference_by_id()
    {
        $eReference = $this->buildSUT();

        $reference = $this->getReferenceByIdServiceOrThrowNotFoundException->execute($eReference->id);

        $this->assertNotNull($reference);
        $this->assertEquals($eReference->id, $reference->id()->value());
    }

    public function test_can_throw_not_found_reference_exception()
    {
        $eReference = $this->buildSUT();

        $this->expectException(NotFoundReferenceException::class);
        $this->getReferenceByIdServiceOrThrowNotFoundException->execute('someFakeId');

    }

    /**
     * @return TheReference
     */
    private function buildSUT(): TheReference
    {
        return TheReference::factory()->create();
    }

}
