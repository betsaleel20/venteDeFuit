<?php

namespace Shop\Basket\Application\Command\Validate;

use Shop\Basket\Domain\Enums\BasketStatus;
use Shop\Basket\Domain\Enums\Currency;
use Shop\Basket\Domain\Enums\PaymentMethod;
use Shop\Basket\Domain\Repository\BasketRepository;
use Shop\Basket\Services\GetBasketByIdOrThrowNotFoundExceptionService;
use Shop\Order\Order;
use Shop\shared\Id;

class ValidateBasketHandler
{


    public function __construct(
        private BasketRepository                             $repository,
        private GetBasketByIdOrThrowNotFoundExceptionService $getBasketOrThrowNotFoundBasketExceptionService,
    )
    {
    }

    public function handle(ValidateBasketCommand $command): ValidateBasketResponse
    {
        $response = new ValidateBasketResponse();

        $basketId = $command->basketId;
        $paymentMethod = PaymentMethod::in($command->paymentMethod);
        $currency = Currency::in($command->currency);
        $basket = $this->getBasketOrThrowNotFoundBasketExceptionService->execute(new Id($basketId));
        $this->

        $order = Order::create(
            basketId: new Id($basketId),
            paymentMethod: $paymentMethod,
            currency: $currency
        );

        $response->isValidated = true;
        $response->orderId = $order->id()->value();
        $basket->unsetElements();
        $basket->changeStatus(BasketStatus::IS_DESTROYED);
        return $response;
    }

}
