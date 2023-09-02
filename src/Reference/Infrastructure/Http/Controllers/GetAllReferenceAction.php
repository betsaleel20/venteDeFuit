<?php

namespace Shop\Reference\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Shop\Reference\Application\Query\GetAllReferencesQueryHandler;

class GetAllReferenceAction
{
   public function __invoke(
       GetAllReferencesQueryHandler $query
   ): JsonResponse
   {
       $response = $query->handle();
       return response()->Json( [
           'status' => true,
           'references' => $response->references
       ]);
   }

}
