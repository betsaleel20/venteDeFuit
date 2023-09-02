<?php

namespace Shop\Reference\Infrastructure\Http\Request;

use Shop\shared\Infrastructure\Request\HttpDataRequest;

class SaveReferenceRequest extends HttpDataRequest
{

    public function messages(): array
    {
        return [
            'label.required' => 'Veuillez renseigner le nom de cette reference',
            'price.required' => 'Veuillez renseigner le prix a appliquer aux produits de cette reference',
        ];
    }
    public function rules(): array
    {
        return [
            'label' => 'required',
            'price' => 'required'
        ];
    }


}
