<?php

namespace Shop\Reference\Application\Command\save;

use Shop\shared\Exceptions\InvalidCommandException;

class SaveReferenceCommand
{
    /**
     * @param string $label
     * @param float $price
     * @param string|null $referenceId
     */
    public function __construct(
        public string  $label,
        public float   $price,
        public ?string $referenceId = null)
    {
        $this->validate();
    }

    private function validate():void
    {
        if(
            empty($this->label) ||
            $this->price <=0
        ){
                throw new InvalidCommandException('Les informations que vous venez d\'entrer sont invalides');
        }
    }
}
