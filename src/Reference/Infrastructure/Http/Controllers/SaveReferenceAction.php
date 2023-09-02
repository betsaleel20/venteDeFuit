<?php

namespace Shop\Reference\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Shop\Reference\Application\Command\save\SaveReferenceHandler;
use Shop\Reference\Infrastructure\Factory\SaveReferenceCommandFactory;
use Shop\Reference\Infrastructure\Http\Request\SaveReferenceRequest;

class SaveReferenceAction
{

    /**
     * @param SaveReferenceRequest $request
     * @param SaveReferenceHandler $handler
     * @return JsonResponse
     */
    public function __invoke(
        SaveReferenceRequest $request,
        SaveReferenceHandler $handler,
    ): JsonResponse
    {
        $command = SaveReferenceCommandFactory::buildReferenceFromHttpRequest($request);
        $response = $handler->handle($command);
        $httpJson = [
            'status' => true,
            'isSaved' => $response->isSaved,
            'referenceId' => $response->referenceId,
        ];

        return response()->json($httpJson);
    }

}
