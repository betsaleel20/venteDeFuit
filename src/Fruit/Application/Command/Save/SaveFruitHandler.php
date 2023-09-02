<?php

namespace Shop\Fruit\Application\Command\Save;

use Shop\Fruit\Domain\Fruit;
use Shop\Fruit\Domain\FruitRepository;
use Shop\Fruit\Domain\Service\GetFruitByIdService;
use Shop\Fruit\Services\EloquentGetFruitByIdService;
use Shop\Reference\Services\PdoGetReferenceByIdService;
use Shop\shared\Id;
use Shop\shared\StringVo;

class SaveFruitHandler
{
    public function __construct(
        private FruitRepository            $respository,
        private PdoGetReferenceByIdService $getReferenceByIdServiceOrThrowNotFoundReferenceException,
        private GetFruitByIdService        $getFruitByIdOrthrowNotFoundFruitExceptionService,
    )
    {
    }

    public function handle(SaveFruitCommand $command): SaveFruitResponse
    {
        $response = new SaveFruitResponse();

        $reference = is_null($command->referenceId) ? null :
            $this->getReferenceByIdServiceOrThrowNotFoundReferenceException->execute(new Id($command->referenceId));
        $fruitId = is_null($command->fruitId) ? null : new Id($command->fruitId);
        $foundFruit = is_null($fruitId) ? null : $this->getFruitByIdOrthrowNotFoundFruitExceptionService->execute($fruitId);
        $fruit = Fruit::create(
            referenceId: $reference->id(),
            fruitName: new StringVo($command->label),
            fruitId: $foundFruit?->id()
        );
        $this->respository->save($fruit);

        $response->isSaved = true;
        $response->fruitId = $fruit->id()->value();
        return $response;
    }
}
