<?php

namespace Shop\Reference\Domain;

use Shop\shared\DateVo;
use Shop\shared\Id;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;

class TheReference
{
    private ?DateVo $createdAt;
    private ?DateVo $deletedAt;
    private function __construct(
        private Id       $id,
        private StringVo $referenceName,
        private PriceVo $referencePrice
    )
    {
        $this->createdAt = null;
        $this->deletedAt = null;
    }

    public static function create(
        StringVo $referenceName,
        PriceVo  $referencePrice,
        ?Id      $referenceId = null
    ): self
    {
        $referenceId = is_null($referenceId) ? new Id() : $referenceId;
        return new self(id: $referenceId, referenceName: $referenceName, referencePrice: $referencePrice);
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function referenceName(): StringVo
    {
        return $this->referenceName;
    }

    public function referencePrice(): PriceVo
    {
        return $this->referencePrice;
    }

    public function changeCreatedAt(DateVo $createdAt):void
    {
        $this->createdAt = $createdAt;
    }

    public function changeDeletedAt(DateVo $deletedAt):void
    {
        $this->deletedAt = $deletedAt;
    }

    public function toArray():array
    {
        return [
            'id' => $this->id->value(),
            'the_reference_label' => $this->referenceName->value(),
            'the_reference_price' => $this->referencePrice->value()
        ];
    }

}
