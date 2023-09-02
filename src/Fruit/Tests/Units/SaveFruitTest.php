<?php

namespace Shop\Fruit\Tests\Units;

use PHPUnit\Framework\TestCase;
use Shop\Fruit\Application\Command\Save\SaveFruitCommand;
use Shop\Fruit\Application\Command\Save\SaveFruitHandler;
use Shop\Fruit\Domain\Exceptions\NotFoundFruitException;
use Shop\Fruit\Domain\Fruit;
use Shop\Fruit\Domain\FruitRepository;
use Shop\Fruit\Domain\Service\GetFruitByIdService;
use Shop\Fruit\Tests\Units\CommandBuilder\SaveFruitCommandBuilder;
use Shop\Fruit\Tests\Units\Services\InMemoryGetFruitByIdOrThrowNotFoundFruitExceptionService;
use Shop\Reference\Domain\Exceptions\NotFoundReferenceException;
use Shop\Reference\Domain\TheReference;
use Shop\Reference\Domain\Repository\ReferenceRepository;
use Shop\Reference\Services\PdoGetReferenceByIdService;
use Shop\Reference\Tests\Unit\InMemoryReferenceRepository;
use Shop\shared\Exceptions\InvalidCommandException;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;

class SaveFruitTest extends TestCase
{
    private FruitRepository $repository;
    private ReferenceRepository $referenceRepository;
    private GetFruitByIdService $inMemoryGetFruitByIdOrThrowNotFoundFruitExceptionService;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryFruitRepository();
        $this->referenceRepository = new InMemoryReferenceRepository();
        $this->inMemoryGetFruitByIdOrThrowNotFoundFruitExceptionService = new InMemoryGetFruitByIdOrThrowNotFoundFruitExceptionService();
    }

    /**
     * @return void
     */
    public function test_can_create_fruit()
    {
        //Given
        $command = $this->buildSUT(fruitName:'Raisin');
        //When
        $handler = $this->createSaveFruitHandler();
        $response = $handler->handle($command);

        //Then
        $this->assertTrue($response->isSaved);
        $this->assertNotNull($response->fruitId);
    }

    /**
     * @return void
     */
    public function test_can_throw_invalid_command_exception()
    {
        //Given
        $this->expectException(InvalidCommandException::class);
        new SaveFruitCommand(
            referenceId: ' ',
            label: ' '
        );
    }

    /**
     * @return void
     */
    public function test_can_throw_not_found_reference_exception()
    {
        //Given
        $command = $this->buildSUT(withExistingReference: false);

        //When && Then
        $handler = $this->createSaveFruitHandler();
        $this->expectException(NotFoundReferenceException::class);
        $handler->handle($command);
    }

    /**
     * @return void
     */
    public function test_can_update_fruit()
    {
        //Given
        $command = $this->buildSUT( withFruitId: true, fruitName: 'Raisin frais' );

        //When
        $handler = $this->createSaveFruitHandler();
        $response = $handler->handle($command);

        //Then
        $this->assertTrue($response->isSaved);
        $this->assertEquals($command->fruitId, $response->fruitId);
    }

    /**
     * @return void
     */
    public function test_can_throw_not_found_fruit_exception()
    {
        //Given
        $command = $this->buildSUT();
        $command->fruitId = 'someBadId';

        //When
        $handler = $this->createSaveFruitHandler();
        $this->expectException(NotFoundFruitException::class);
        $handler->handle($command);
    }

    /**
     * @param bool $withExistingReference
     * @param bool $withFruitId
     * @param string|null $fruitName
     * @return SaveFruitCommand
     */
    private function buildSUT(
        bool $withExistingReference = true,
        bool $withFruitId = false,
        ?string $fruitName = null
    ): SaveFruitCommand
    {
        $reference1 = TheReference::create(
            referenceName: new StringVo('Ref001'),
            referencePrice: new PriceVo(1200)
        );
        $reference2 = TheReference::create(
            referenceName: new StringVo('Ref002'),
            referencePrice: new PriceVo(5000)
        );
        $this->referenceRepository->save($reference1);
        $this->referenceRepository->save($reference2);

        $fruit = Fruit::create(
            referenceId: $reference1->id(),
            fruitName: new StringVo('Pastèque')
        );
        $this->inMemoryGetFruitByIdOrThrowNotFoundFruitExceptionService
            ->fruits[$fruit->id()->value()][$fruit->referenceId()->value()] = $fruit;

        $command = SaveFruitCommandBuilder::asBuilder();

        is_null($fruitName) ? : $command->withName($fruitName);
        !$withFruitId ? : $command->withFruitId($fruit->id()->value());
        !$withExistingReference ? : $command->withReferenceId($reference1->id()->value());

        return $command->build();
    }

    /**
     * @return SaveFruitHandler
     */
    public function createSaveFruitHandler(): SaveFruitHandler
    {
        return new SaveFruitHandler(
            $this->repository,
            new PdoGetReferenceByIdService($this->referenceRepository),
            $this->inMemoryGetFruitByIdOrThrowNotFoundFruitExceptionService,
        );
    }
}
