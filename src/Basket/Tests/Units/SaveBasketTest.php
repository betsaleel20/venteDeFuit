<?php

namespace Shop\Basket\Tests\Units;

use PHPUnit\Framework\TestCase;
use Shop\Basket\Application\Command\Save\SaveBasketHandler;
use Shop\Basket\Domain\Basket;
use Shop\Basket\Domain\Exceptions\NotFoundBasketException;
use Shop\Basket\Domain\Repository\BasketRepository;
use Shop\Basket\Domain\ValuesObject\BasketElement;
use Shop\Basket\Domain\ValuesObject\Quantity;
use Shop\Basket\Tests\Units\CommandBuilder\SaveBasketCommandBuilder;
use Shop\Basket\Tests\Units\Repository\InMemoryBasketRepository;
use Shop\Fruit\Domain\Exceptions\UnavailableFruitQuantityException;
use Shop\Fruit\Domain\Fruit;
use Shop\Fruit\Domain\FruitRepository;
use Shop\Fruit\Services\CheckFruitInStockAvailabilityService;
use Shop\Fruit\Tests\Units\InMemoryFruitRepository;
use Shop\Reference\Domain\Exceptions\NotFoundReferenceException;
use Shop\Reference\Domain\Repository\ReferenceRepository;
use Shop\Reference\Domain\TheReference;
use Shop\Reference\Services\PdoGetReferenceByIdService;
use Shop\Reference\Tests\Unit\InMemoryReferenceRepository;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;

class SaveBasketTest extends TestCase
{
    private BasketRepository $repository;
    private FruitRepository $fruitRepository;
    private ReferenceRepository $referenceRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryBasketRepository();
        $this->fruitRepository = new InMemoryFruitRepository();
        $this->referenceRepository = new InMemoryReferenceRepository();
    }


    public function test_can_create_basket()
    {
        //Given
        $data = $this->buildSUT(
            withReferenceId: true,
            withQuantity: 2,
        );
        $command = $data['command'];
        // When
        $handler = $this->saveBasketHandler();
        $response = $handler->handle($command);

        //Then
        $this->assertTrue($response->isSaved);
        $this->assertNotNull($response->basketId);
    }

    public function test_can_add_element_to_basket()
    {
        //Given
        $data = $this->buildSUT(withQuantity: 6, withGoodBasketId: true);
        $command = $data['command'];
        $referenceId = $data['otherReference']->id()->value();
        $command->fruitRef = $referenceId;

        //When
        $handler = $this->saveBasketHandler();
        $response = $handler->handle($command);

        //Then
        $this->assertTrue($response->isSaved);
        $this->assertArrayHasKey($referenceId, $data['basket']->basketElements());
    }

    public function test_can_throw_not_found_reference_exception()
    {
        //Given
        $data = $this->buildSUT( withQuantity: 3, withGoodBasketId: true);
        $command = $data['command'];

        //When && Then
        $handler = $this->saveBasketHandler();
        $this->expectException(NotFoundReferenceException::class);
        $handler->handle($command);
    }

    public function test_can_throw_unavailable_quantity_exception()
    {
        //Given
        $data = $this->buildSUT( withReferenceId: true, withQuantity: 30, withGoodBasketId: true);
        $command = $data['command'];

        //When && Then
        $handler = $this->saveBasketHandler();
        $this->expectException(UnavailableFruitQuantityException::class);
        $handler->handle($command);
    }

    public function test_can_throw_not_found_basket_exception()
    {
        //Given
        $data = $this->buildSUT( withReferenceId: true, withQuantity: 3 );
        $command = $data['command'];
        $command->basketId = 'badBasketId';

        //When && Then
        $handler = $this->saveBasketHandler();
        $this->expectException(NotFoundBasketException::class);
        $handler->handle($command);
    }

    public function test_can_update_element_quantity_in_basket()
    {
        //Given
        $quantity = 10;
        $data = $this->buildSUT( withReferenceId: true, withQuantity: $quantity, withGoodBasketId: true );
        $command = $data['command'];

        //When
        $handler = $this->saveBasketHandler();
        $response = $handler->handle($command);

        $this->assertTrue($response->isSaved);
        $this->assertEquals($quantity, $data['basket']->basketElements()[$command->fruitRef]->quantity()->value());
    }

    public function test_can_remove_element_from_basket()
    {
        //Given
        $data = $this->buildSUT( withReferenceId: true, withQuantity: 0, withGoodBasketId: true );
        $command = $data['command'];

        //When
        $handler = $this->saveBasketHandler();
        $response = $handler->handle($command);

        $this->assertTrue($response->isSaved);
        $this->assertArrayNotHasKey($command->fruitRef, $data['basket']->basketElements());

    }

    /**
     * @param bool $withReferenceId
     * @param int|null $withQuantity
     * @param bool $withGoodBasketId
     * @return array
     */
    private function buildSUT(
        bool $withReferenceId = false,
        ?int $withQuantity = null,
        bool $withGoodBasketId = false,
    ): array
    {
        $reference1 = TheReference::create(
            referenceName: new StringVo('Ref001'),
            referencePrice: new PriceVo(5000)
        );
        $reference2 = TheReference::create(
            referenceName: new StringVo('Ref001'),
            referencePrice: new PriceVo(17000)
        );
        $this->referenceRepository->save($reference1);
        $this->referenceRepository->save($reference2);


        $basketElement = new BasketElement(
            referenceId: $reference1->id(),
            quantity: new Quantity(4)
        );
        $basket = Basket::create($basketElement);
        $this->repository->save($basket);

        $fruits1 = $this->createManyFruits($reference1, 20);
        $fruits2 = $this->createManyFruits($reference2, 15);

        $this->fruitRepository->saveMany($fruits1);
        $this->fruitRepository->saveMany($fruits2);

        $command = SaveBasketCommandBuilder::asBuilder();

        if ($withReferenceId) {
            $command->withReferenceId($reference1->id()->value());
        }

        if ($withQuantity >= 0) {
            $command->withQuantity($withQuantity);
        }

        if ($withGoodBasketId) {
            $command->withBasketId($basket->id()->value());
        }

        return [
            "command" => $command->build(),
            "otherReference" => $reference2,
            'basket' => $basket,
        ];
    }

    /**
     * @return SaveBasketHandler
     */
    private function saveBasketHandler(): SaveBasketHandler
    {
        return new SaveBasketHandler(
            $this->repository,
            new PdoGetReferenceByIdService($this->referenceRepository),
            new CheckFruitInStockAvailabilityService(
                $this->fruitRepository,
            )
        );
    }

    /**
     * @param TheReference $reference
     * @param int $number
     * @return Fruit[]
     */
    public function createManyFruits(TheReference $reference, int $number): array
    {
        $fruits = [];
        $i = 0;
        while ($i < $number) {
            $fruits[] = Fruit::create(
                referenceId: $reference->id(),
                fruitName: new StringVo('Banane')
            );
            $i++;
        }
        return $fruits;
    }
}
