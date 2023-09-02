<?php

namespace Shop\Reference\Application\Command\save;

use Shop\shared\Exceptions\InvalidCommandException;

class SaveReferenceCommand
{

    public ?string $referenceId ;
    /**
     * @param string $label
     * @param float $price
     */
    public function __construct(
        public string  $label,
        public float   $price,
    )
    {
        $this->referenceId = null;
        $this->validate();
    }

    private function validate():void
    {
        if(
            empty(trim($this->label)) ||
            $this->price <=0
        ){
                throw new InvalidCommandException('Les informations que vous venez d\'entrer sont invalides');
        }
    }
}
