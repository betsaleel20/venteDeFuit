<?php

namespace Shop\Reference\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Shop\Reference\Domain\Repository\ReferenceRepository;
use Shop\Reference\Infrastructure\Model\TheReference;
use Shop\Reference\Domain\TheReference as DomainReference;
use Shop\Reference\Infrastructure\Repository\EloquentReferenceRepository;
use Shop\shared\Id;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;
use Tests\TestCase;

class EloquentReferenceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ReferenceRepository $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentReferenceRepository();
    }

    public function test_can_get_reference_by_id()
    {
        $reference = TheReference::factory()->create();

        $existingReference = $this->repository->byId(new Id($reference->id));

        $this->assertNotNull($existingReference);
        $this->assertEquals($reference->id, $existingReference->id()->value());
    }

    public function test_can_create_reference()
    {
        $reference = DomainReference::create(
            referenceName: new StringVo('My product reference Label'),
            referencePrice: new PriceVo(500)
        );
        $this->repository->save($reference);
        $existingReference = $this->repository->byId($reference->id());

        $this->assertNotNull($existingReference);
    }

    public function test_can_update_reference()
    {
        $existingReference = $this->buildSUT();
        $referenceToSave = DomainReference::create(
            referenceName: new StringVo('new Label'),
            referencePrice: new PriceVo(4500),
            referenceId:$existingReference->id(),
        );

        $this->repository->save($referenceToSave);
        $existingReference = $this->repository->byId($referenceToSave->id());
        $this->assertEquals($referenceToSave->id(), $existingReference->id());
        $this->assertEquals($referenceToSave->referenceName(), $existingReference->referenceName());
        $this->assertEquals($referenceToSave->referencePrice(), $existingReference->referencePrice());
    }

    private function buildSUT(): DomainReference
    {
        return DomainReference::create(
            referenceName: new StringVo('My TheReference Label'),
            referencePrice: new PriceVo(50)
        );
    }


}
