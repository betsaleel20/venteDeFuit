<?php

namespace Shop\Fruit\Services;

use Shop\Basket\Domain\ValuesObject\Quantity;
use Shop\Fruit\Domain\Exceptions\UnavailableFruitQuantityException;
use Shop\Fruit\Domain\FruitRepository;
use Shop\Reference\Domain\Exceptions\NotFoundReferenceException;
use Shop\Reference\Domain\TheReference;

readonly class CheckFruitInStockAvailabilityService
{
    public function __construct(
        private FruitRepository $fruitRepository,
    )
    {
    }

    public function execute(TheReference $reference, Quantity $quantity):void
    {
        $availableFruits = $this->fruitRepository->byReference(  $reference );
        if(empty($availableFruits)){
            throw new NotFoundReferenceException('Aucun fruit avec cette');
        }

        $minimalStockQuantity = 5;
        if($quantity->value() + $minimalStockQuantity > count($availableFruits) ){
            throw new UnavailableFruitQuantityException('Quantité insuffisante! Vous ne pouvez pas acheter \n
            jusqu\'à <'.$quantity->value().'> fruits de cette reference.');
        }
    }
}
