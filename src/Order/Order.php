<?php

namespace Shop\Order;

use Shop\Basket\Domain\Enums\Currency;
use Shop\Basket\Domain\Enums\PaymentMethod;
use Shop\shared\Id;

class Order
{

    public function __construct(
        private readonly Id $id,
        private Id $basketId,
        private PaymentMethod $paymentMethod,
        private Currency $currency
    )
    {
    }

    public static function create(
        Id $basketId,
        PaymentMethod $paymentMethod,
        Currency $currency,
        ?Id $orderId = null,
    ):self
    {
        $orderId = is_null($orderId) ? new Id(uniqid('Order')) : $orderId;
        return new self( $orderId, $basketId, $paymentMethod, $currency );
    }

    public function id():Id
    {
        return $this->id;
    }
}
