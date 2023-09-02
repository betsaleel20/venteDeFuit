<?php

namespace Shop\Basket\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Shop\Basket\Domain\Exceptions\ErrorOnSaveBasketException;
use Shop\Basket\Domain\Repository\BasketRepository;
use Shop\Basket\Domain\ValuesObject\BasketElement;
use Shop\Basket\Domain\ValuesObject\Quantity;
use Shop\Basket\Infrastructure\Basket;
use Shop\Basket\Domain\Basket as DomainBasket;
use Shop\Basket\Infrastructure\repository\EloquentBasketRepository;
use Shop\Reference\Domain\Repository\ReferenceRepository;
use Shop\Reference\Domain\TheReference;
use Shop\Reference\Infrastructure\Repository\EloquentReferenceRepository;
use Shop\shared\Id;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;
use Tests\TestCase;

class EloquentBasketRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private BasketRepository $repository;
    private ReferenceRepository $referenceRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentBasketRepository();
        $this->referenceRepository = new EloquentReferenceRepository();
    }

    public function test_can_get_basket_by_id()
    {
        $basket = Basket::factory()->create();

        $foundBasket = $this->repository->byId(new Id($basket->id));

        $this->assertNotNull($foundBasket);
        $this->assertEquals( $basket->id , $foundBasket->id()->value());
    }

    /**
     * @throws ErrorOnSaveBasketException
     */
    public function test_can_create_basket()
    {
        $data = $this->buildSUT();
        $basketElement = new  BasketElement($data['basketElement']->referenceId(), new Quantity(12));
        $basket = DomainBasket::create($basketElement);

        $this->repository->save($basket);
        $foundBasket = $this->repository->byId(basketId: $basket->id());

        $this->assertNotNull($foundBasket);
        $this->assertEquals($basket->id()->value(), $foundBasket->id()->value());
    }

    /**
     * @throws ErrorOnSaveBasketException
     */
    public function buildSUT(): array
    {
        $reference = TheReference::create(
            referenceName: new StringVo('Ref01'),
            referencePrice:  new PriceVo(1250),
        );
        $this->referenceRepository->save($reference);

        $basketElement = new BasketElement($reference->id(), new Quantity(7));
        $basket = DomainBasket::create($basketElement);
        $this->repository->save($basket);

        return [
            'basket' => $basket,
            'basketElement' => $basketElement
        ];

    }
}
