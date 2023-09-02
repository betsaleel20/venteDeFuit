<?php

namespace Shop\Fruit\Domain;

use Shop\Fruit\Domain\Enums\FruitStatus;
use Shop\shared\DateVo;
use Shop\shared\Id;
use Shop\shared\StringVo;

class Fruit
{

    private ?DateVo $updatedAt;

    /**
     * @param Id $id
     * @param Id $referenceId
     * @param StringVo $fruitName
     * @param DateVo $createdAt
     * @param FruitStatus $status
     */
    private function __construct(
        private Id          $id,
        private Id          $referenceId,
        private StringVo    $fruitName,
        private DateVo      $createdAt,
        private FruitStatus $status,
    )
    {
        $this->updatedAt = null;
    }

    /**
     * @param Id $referenceId
     * @param StringVo $fruitName
     * @param Id|null $fruitId
     * @param DateVo|null $createdAt
     * @param FruitStatus|null $status
     * @return self
     */
    public static function create(
        Id           $referenceId,
        StringVo     $fruitName,
        ?Id          $fruitId = null,
        ?DateVo      $createdAt = null,
        ?FruitStatus $status = null
    ): self
    {
            $fruitId ?? $fruitId = new Id();
            $status ?? $status = FruitStatus::AVAILABLE;
            $createdAt ?? $createdAt = new DateVo();
        return new self($fruitId, $referenceId, $fruitName, $createdAt, $status);
    }

    /**
     * @return Id
     */
    public function id(): Id
    {
        return $this->id;
    }

    public function referenceId(): Id
    {
        return $this->referenceId;
    }

    public function updatedAt(DateVo $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @throws \Exception
     */
    public function toArray():array
    {
        return [
            'id' => $this->id->value(),
            'product_reference_id' => $this->referenceId->value(),
            'fruit_name' => $this->fruitName->value(),
            'created_at' => $this->createdAt->formatYMDHIS(),
            'updated_at' => $this->updatedAt?->formatYMDHIS()
        ];
    }

    public function fruitName():StringVo
    {
        return $this->fruitName;
    }

}
