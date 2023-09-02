<?php

namespace Shop\Basket\Infrastructure\repository;

use Exception;
use Illuminate\Support\Facades\DB;
use Shop\Basket\Domain\Basket as DomainBasket;
use Shop\Basket\Domain\Exceptions\ErrorOnSaveBasketException;
use Shop\Basket\Domain\Repository\BasketRepository;
use Shop\Basket\Domain\ValuesObject\BasketElement;
use Shop\Basket\Infrastructure\Basket;
use Shop\shared\Id;
use Throwable;

class EloquentBasketRepository implements BasketRepository
{

    /**
     * @throws ErrorOnSaveBasketException
     */
    public function save(DomainBasket $basket): void
    {
        try {
            $referencesIds = array_keys($basket->basketElements());
            $quantities = array_map(
                fn(BasketElement $element) => $element->quantity()->value(),
                $basket->basketElements()
            );

//            dd($referencesIdsQuantities);
            DB::transaction(function() use ( $basket, $referencesIds, $quantities ){
                $eBasket = Basket::whereId($basket->id()->value())->first();
                $eBasket = $eBasket ?
                    $eBasket->fill($basket->toArray())
                    :
                    (new Basket())->fill($basket->toArray());
                $eBasket->save();
                $eBasket->the_references()->detach();
                array_map(
                    fn( string $referenceId , int $quantity) => $eBasket->the_references()
                        ->attach($referenceId , ['quantity' => $quantity]),
                    $referencesIds, $quantities
                );
            });

        }catch (Throwable | Exception $e){
            throw new ErrorOnSaveBasketException($e->getMessage());
        }

    }

    public function byId(Id $basketId): ?DomainBasket
    {
        return Basket::whereId($basketId->value())->first()?->toDomain();
    }
}
