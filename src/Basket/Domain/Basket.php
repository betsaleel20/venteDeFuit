<?php

namespace Shop\Basket\Domain;

use Shop\Basket\Domain\Enums\BasketStatus;
use Shop\Basket\Domain\Exceptions\InvalidCommandException;
use Shop\Basket\Domain\ValuesObject\BasketElement;
use Shop\shared\DateVo;
use Shop\shared\Id;

class Basket
{

    /**
     * @var BasketElement[]
     */
    private array $basketElements;
    private ?DateVo $updatedAt = null;
    private BasketStatus $status;

    /**
     * @param Id $id
     * @param DateVo $createdAt
     */
    public function __construct(private readonly Id $id, private readonly DateVo $createdAt)
    {
        $this->basketElements = [];
    }

    /**
     * @param BasketElement $basketElement
     * @param Basket|null $basket
     * @return self
     */
    public static function create(BasketElement $basketElement, ?Basket $basket = null): self
    {
        if ($basketElement->quantity()->value() === 0) {
            is_null($basket) ?
                throw new InvalidCommandException('Suppression impossible ! Ce panier n\'existe pas.') :
                $basket->removeElementFromBasket($basketElement);

            return $basket;
        }
        if ($basket) {
            $basket->addElementToBasket($basketElement);
            $basket->updatedAt = new DateVo();

            return $basket;
        }
        $self = new self(new Id(), new DateVo());
        $self->addElementToBasket($basketElement);
        $self->changeStatus(BasketStatus::IS_SAVED);

        return $self;
    }

    /**
     * @param BasketElement $basketElement
     * @return void
     */
    public function addElementToBasket(BasketElement $basketElement): void
    {
        $this->basketElements[$basketElement->referenceId()->value()] = $basketElement;
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return BasketElement[]
     */
    public function basketElements(): array
    {
        return $this->basketElements;
    }

    /**
     * @param BasketElement $basketElement
     * @return void
     */
    public function removeElementFromBasket(BasketElement $basketElement): void
    {
        unset($this->basketElements[$basketElement->referenceId()->value()]);

        count($this->basketElements) > 0 ?
            $this->updatedAt = new DateVo() :
            $this->changeStatus(BasketStatus::IS_DESTROYED);
    }

    public function changeStatus(BasketStatus $status): void
    {
        $this->status = $status;
    }

    public function status(): BasketStatus
    {
        return $this->status;
    }

    public function unsetElements(): void
    {
        $this->basketElements = [];
    }

    public function changeUpdatedAt(DateVo $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'status' => $this->status->value,
            'created_at' => $this->createdAt?->formatYMDHIS(),
            'updated_at' => $this->updatedAt?->formatYMDHIS()
        ];
    }
}
