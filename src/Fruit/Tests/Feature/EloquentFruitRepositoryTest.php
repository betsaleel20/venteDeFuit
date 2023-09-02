<?php

namespace Shop\Fruit\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Shop\Fruit\Domain\FruitRepository;
use Shop\Fruit\Infrastructure\Model\Fruit;
use Shop\Fruit\Domain\Fruit as DomainFruit;
use Shop\Fruit\Infrastructure\Repository\EloquentFruitRepository;
use Shop\Fruit\Services\EloquentGetFruitByIdService;
use Shop\Reference\Infrastructure\Model\TheReference;
use Shop\Reference\Domain\TheReference as TheReferenceDomain;
use Shop\shared\Id;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;
use Tests\TestCase;

class EloquentFruitRepositoryTest extends TestCase
{
    use RefreshDatabase;
    private FruitRepository $repository;
    private EloquentGetFruitByIdService $getFruitByIdServiceOrThrowNotFoundFruitException;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentFruitRepository();
        $this->getFruitByIdServiceOrThrowNotFoundFruitException = new EloquentGetFruitByIdService($this->repository);
    }

    public function test_can_get_fruit_by_id()
    {
        $initData = $this->buildSUT()['fruit'];
        $existingFruit = $initData[0];

        $foundFruit = $this->repository->byId(new Id($existingFruit->id));

        $this->assertNotNull($foundFruit);
        $this->assertEquals($foundFruit->id()->value(), $existingFruit->id);
    }

    public function test_can_get_fruit_by_reference()
    {
        $initData = $this->buildSUT();
        $existingReference = $initData['reference'];
        $domainReference = TheReferenceDomain::create(
            referenceName: new StringVo($existingReference->the_reference_label),
            referencePrice: new PriceVo(5000),
            referenceId: new Id($existingReference->id)
        );
        $foundFruit = $this->repository->byReference($domainReference);
        $this->assertNotNull($foundFruit);
        $this->assertEquals($foundFruit[0]->referenceId()->value() , $existingReference->id);
    }

    public function test_can_create_fruit()
    {
        $reference = $this->buildSUT()['reference'];
        $fruit = DomainFruit::create(
            referenceId: new Id($reference->id),
            fruitName: new StringVo('Pastèque')
        );

        $this->repository->save($fruit);
        $savedFruit = $this->repository->byId($fruit->id());

        $this->assertNotNull($savedFruit);
        $this->assertEquals($fruit->id()->value(), $savedFruit->id()->value());
        $this->assertEquals($fruit->referenceId()->value(), $reference->id);
    }

    public function test_can_update_fruit()
    {
        $existingFruit = $this->buildSUT()['fruit'];
        $inputData = DomainFruit::create(
            referenceId: new Id($existingFruit[0]->the_reference_id),
            fruitName: new StringVo('My new fruit name'),
            fruitId: new Id($existingFruit[0]->id)
        );

        $this->repository->save($inputData);
        $updatedFruit = $this->repository->byId($inputData->id());

        $this->assertNotNull($updatedFruit);
        $this->assertEquals($inputData->id()->value(), $updatedFruit->id()->value());
        $this->assertEquals($inputData->fruitName()->value(), $updatedFruit->fruitName()->value());
    }

    /**
     * @return array
     */
    public function buildSUT(): array
    {
        $existingReference = TheReference::factory()->create();
        $fruits = Fruit::factory(5)->create(
            ['product_reference_id' => $existingReference->id]
        );

        return [
            'fruit' => $fruits,
            'reference' => $existingReference
        ];
    }
}
