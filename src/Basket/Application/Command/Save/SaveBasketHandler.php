<?php

namespace Shop\Basket\Application\Command\Save;

use Shop\Basket\Domain\Basket;
use Shop\Basket\Domain\Exceptions\NotFoundBasketException;
use Shop\Basket\Domain\Repository\BasketRepository;
use Shop\Basket\Domain\ValuesObject\BasketElement;
use Shop\Basket\Domain\ValuesObject\Quantity;
use Shop\Fruit\Services\CheckFruitInStockAvailabilityService;
use Shop\Reference\Services\GetReferenceByIdService;
use Shop\shared\Id;

readonly class SaveBasketHandler
{


    public function __construct(
        private BasketRepository                     $repository,
        private GetReferenceByIdService $getReferenceByIdServiceOrThrowNotFoundException,
        private CheckFruitInStockAvailabilityService $checkFruitInStockAvailabilityOrThrowUnavailableFruitQuantityException
    )
    {
    }

    public function handle(SaveBasketCommand $command): SaveBasketResponse
    {
        $response = new SaveBasketResponse();
        $existingBasket = $this->checkIfBasketExistOrThrowNotFoundBasketException($command->basketId);

        $referenceId = new Id($command->fruitRef);
        $quantity = is_null($command->quantity) ? new Quantity(0) : new Quantity($command->quantity);

        $reference = $this->getReferenceByIdServiceOrThrowNotFoundException->execute($referenceId);
        $this->checkFruitInStockAvailabilityOrThrowUnavailableFruitQuantityException->execute($reference, $quantity);

        $basketElement = new BasketElement($referenceId, $quantity);
        $basket = Basket::create($basketElement, $existingBasket);

        $response->isSaved = true;
        $response->basketId = $basket->id()->value();
        return $response;
    }

    /**
     * @param string|null $basketId
     * @return Basket|null
     */
    public function checkIfBasketExistOrThrowNotFoundBasketException(?string $basketId): ?Basket
    {
        if (!$basketId) {
            return null;
        }
        $existingBasket = $this->repository->byId(new Id($basketId));
            $existingBasket ?? throw new NotFoundBasketException('Le panier que vous souhaitez manipuler n\'existe pas!');
        return $existingBasket;
    }

}
