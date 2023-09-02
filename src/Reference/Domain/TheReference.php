<?php

namespace Shop\Reference\Domain;

use Shop\shared\DateVo;
use Shop\shared\Id;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;

class TheReference
{
    private ?DateVo $createdAt;
    private ?DateVo $updatedAt;

    public function __construct(
        private Id       $id,
        private StringVo $referenceName,
        private PriceVo  $referencePrice
    )
    {
        $this->createdAt = null;
        $this->updatedAt = null;
    }

    public static function create(
        StringVo $referenceName,
        PriceVo  $referencePrice,
        ?Id      $referenceId = null,
    ): self
    {
        $referenceId = is_null($referenceId) ? new Id() : $referenceId;
        $self = new self(id: $referenceId, referenceName: $referenceName, referencePrice: $referencePrice);
        $referenceId ? $self->changeUpdatedAt(new DateVo()) : $self->createdAt = new DateVo();
        return $self;
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    /**
     * @return StringVo
     */
    public function referenceName(): StringVo
    {
        return $this->referenceName;
    }

    /**
     * @return PriceVo
     */
    public function referencePrice(): PriceVo
    {
        return $this->referencePrice;
    }

    /**
     * @return DateVo
     */
    public function createdAt(): DateVo
    {
        return $this->createdAt;
    }

    /**
     * @param DateVo $deletedAt
     * @return void
     */
    public function changeUpdatedAt(DateVo $deletedAt): void
    {
        $this->updatedAt = $deletedAt;
    }

    /**
     * @param StringVo $referenceName
     * @return void
     */
    public function changeReferenceName(StringVo $referenceName):void
    {
        $this->referenceName = $referenceName;
    }

    /**
     * @param PriceVo $referencePrice
     * @return void
     */
    public function changeReferencePrice(PriceVo $referencePrice): void
    {
        $this->referencePrice = $referencePrice;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'the_reference_label' => $this->referenceName->value(),
            'the_reference_price' => $this->referencePrice->value()
        ];
    }


}
