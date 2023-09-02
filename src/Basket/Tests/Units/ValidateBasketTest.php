<?php

namespace Shop\Basket\Tests\Units;

use PHPUnit\Framework\TestCase;
use Shop\Basket\Application\Command\Validate\ValidateBasketCommand;
use Shop\Basket\Application\Command\Validate\ValidateBasketHandler;
use Shop\Basket\Domain\Basket;
use Shop\Basket\Domain\Enums\BasketStatus;
use Shop\Basket\Domain\Enums\Currency;
use Shop\Basket\Domain\Enums\PaymentMethod;
use Shop\Basket\Domain\Exceptions\InvalidArgumentsException;
use Shop\Basket\Domain\Exceptions\NotFoundBasketException;
use Shop\Basket\Domain\Repository\BasketRepository;
use Shop\Basket\Domain\ValuesObject\BasketElement;
use Shop\Basket\Domain\ValuesObject\Quantity;
use Shop\Basket\Services\GetBasketByIdOrThrowNotFoundExceptionService;
use Shop\Basket\Tests\Units\Repository\InMemoryBasketRepository;
use Shop\Fruit\Domain\Exceptions\UnavailableFruitQuantityException;
use Shop\Reference\Domain\TheReference;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;

class ValidateBasketTest extends TestCase
{
    private BasketRepository $repository;
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryBasketRepository();
    }

    public function test_can_validate_basket()
    {
        $initData = $this->buildSUT();
        $basketSUT = $initData['basket'];
        $command = new ValidateBasketCommand(
            basketId: $basketSUT->id()->value(),
            paymentMethod: PaymentMethod::VISA->value,
            currency: Currency::DOLLAR->value
        );

        $handler = $this->createValidateBasketHandler();
        $response = $handler->handle($command);

        $this->assertTrue($response->isValidated);
        $this->assertEmpty($basketSUT->basketElements());
        $this->assertEquals(BasketStatus::IS_DESTROYED->value, $basketSUT->status()->value);
        $this->assertNotNull($response->orderId);
    }

    public function test_can_throw_invalid_argument_exception()
    {
        $this->buildSUT();
        $command = new ValidateBasketCommand(
            basketId: '12',
            paymentMethod: 30,
            currency: 2000
        );

        $handler = $this->createValidateBasketHandler();
        $this->expectException(InvalidArgumentsException::class);
        $handler->handle($command);
    }

    public function test_can_throw_not_found_basket_exception()
    {
        $this->buildSUT();
        $badBasketId = '213';
        $command = new ValidateBasketCommand(
            basketId: $badBasketId,
            paymentMethod: PaymentMethod::VISA->value,
            currency: Currency::DOLLAR->value
        );

        $handler = $this->createValidateBasketHandler();
        $this->expectException(NotFoundBasketException::class);
        $handler->handle($command);
    }

    /**
     * @return void
     */
    public function test_can_throw_unavailable_fruit_quantity_exception_when_validating_basket()
    {
        //given
        $initData = $this->buildSUT();
        $basket = $initData['basket'];
        $newElement = new BasketElement($initData['reference']->id(), new Quantity(500));
        $basket->addElementToBasket($newElement);
        $command = new ValidateBasketCommand(
            basketId:             $basket->id()->value(),
            paymentMethod:  PaymentMethod::VISA->value,
            currency:       Currency::DOLLAR->value
        );

        //When && Then
        $this->expectException(UnavailableFruitQuantityException::class);
        $handler = $this->createValidateBasketHandler();
        $handler->handle($command);
    }

    /**
     * @return array
     */
    private function buildSUT(): array
    {
        $reference1 = TheReference::create( new StringVo('Ref001'), new PriceVo(5000), );
        $reference2 = TheReference::create( new StringVo('Ref202'), new PriceVo(7250) );

        $element1 = new BasketElement(
            referenceId: $reference1->id(),
            quantity: new Quantity(7)
        );
        $basket = Basket::create(
            $element1,
        );

        $element2 = new BasketElement(
            referenceId: $reference2->id(),
            quantity: new Quantity(3)
        );;
        $basket->addElementToBasket($element2);

        $this->repository->save($basket);

        return [
            'basket' => $basket,
            'reference' => $reference1
        ];
    }

    /**
     * @return ValidateBasketHandler
     */
    private function createValidateBasketHandler(): ValidateBasketHandler
    {
        return new ValidateBasketHandler(
            $this->repository,
            new GetBasketByIdOrThrowNotFoundExceptionService($this->repository),
        );
    }
}
