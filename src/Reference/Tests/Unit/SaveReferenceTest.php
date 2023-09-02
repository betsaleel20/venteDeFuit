<?php

namespace Shop\Reference\Tests\Unit;

use Shop\Reference\Application\Command\save\SaveReferenceCommand;
use Shop\Reference\Application\Command\save\SaveReferenceHandler;
use Shop\Reference\Domain\Exceptions\NotFoundReferenceException;
use Shop\Reference\Domain\Service\GetReferenceByIdService;
use Shop\Reference\Domain\TheReference;
use Shop\Reference\Domain\Repository\ReferenceRepository;
use Shop\Reference\Tests\Unit\Services\InMemoryGetReferenceByIdOrThrowNotFoundReferenceExceptionService;
use Shop\shared\Exceptions\InvalidCommandException;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;
use Tests\TestCase;

class SaveReferenceTest extends TestCase
{
    private ReferenceRepository $repository;
    private GetReferenceByIdService $getReferenceByIdService;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryReferenceRepository();
        $this->getReferenceByIdService = new InMemoryGetReferenceByIdOrThrowNotFoundReferenceExceptionService();
    }

    public function test_can_create_reference()
    {
        // Given
        $label = 'Ref001';
        $prix = 1750.0;
        $command = new SaveReferenceCommand($label, $prix);

        //When
        $handler = $this->createSaveReferenceHandler();
        $response = $handler->handle($command);

        //Then
        $this->assertTrue($response->isSaved);
        $this->assertNotNull($response->referenceId);
    }

    public function test_can_throw_invalid_argument_exception()
    {
        $this->expectException(InvalidCommandException::class);
        new SaveReferenceCommand(
            label: ' ',
            price:0,
        );
    }

    public function test_can_throw_not_found_reference_exception()
    {
        //Given
        $referenceSUT = $this->buildReferenceSUT();
        $command = new SaveReferenceCommand(
            label: $referenceSUT->referenceName()->value(),
            price:$referenceSUT->referencePrice()->value()
        );
        $fakeReferenceId = 'someFakeId';
        $command->referenceId = $fakeReferenceId;

        //When
        $handler = $this->createSaveReferenceHandler();
        $this->expectException(NotFoundReferenceException::class);
        $handler->handle($command);
    }

    public function test_can_update_reference()
    {
        //Given
        $referenceSUT = $this->buildReferenceSUT();
        $command = new SaveReferenceCommand(
            label: 'new label',
            price: 200
        );
        $command->referenceId = $referenceSUT->id()->value();

        //When
        $handler = $this->createSaveReferenceHandler();
        $response = $handler->handle($command);
        $updatedReference = $this->repository->references[$command->referenceId];

        //Then
        $this->assertTrue($response->isSaved);
        $this->assertEquals($command->referenceId, $response->referenceId);
        $this->assertEquals($command->label, $updatedReference->referenceName()->value());
        $this->assertEquals($command->price, $updatedReference->referencePrice()->value());
    }

    private function buildReferenceSUT():TheReference
    {
        $reference = TheReference::create(
            referenceName: new StringVo('RefLabel'),
            referencePrice:new PriceVo(1500.50)
        );
        $this->repository->save($reference);
        $this->getReferenceByIdService->references[$reference->id()->value()] = $reference;
        return $reference;
    }

    /**
     * @return SaveReferenceHandler
     */
    private function createSaveReferenceHandler(): SaveReferenceHandler
    {
        return new SaveReferenceHandler(
            $this->getReferenceByIdService,
            $this->repository
        );
    }
}
