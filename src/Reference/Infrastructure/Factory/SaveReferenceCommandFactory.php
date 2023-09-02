<?php

namespace Shop\Reference\Infrastructure\Factory;

use Shop\Reference\Application\Command\save\SaveReferenceCommand;
use Shop\Reference\Infrastructure\Http\Request\SaveReferenceRequest;

class SaveReferenceCommandFactory
{
    public static function buildReferenceFromHttpRequest(SaveReferenceRequest $request): SaveReferenceCommand
    {
        return new SaveReferenceCommand(
            label: $request->get('label'),
            price: $request->get('price'),
        );
    }
}
