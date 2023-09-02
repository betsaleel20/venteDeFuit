<?php

namespace Shop\Fruit\Application\Command\Save;

use Shop\shared\Exceptions\InvalidCommandException;

class SaveFruitCommand
{
    /**
     * @param string $referenceId
     * @param string $label
     * @param string|null $fruitId
     */
    public function __construct(
        public string  $referenceId,
        public string  $label,
        public ?string $fruitId = null
    )
    {
        $this->validate();
    }

    private function validate():void
    {
        if(empty(trim($this->referenceId)) || empty(trim($this->label))){
            throw new InvalidCommandException("Veuillez entrer des informations correctes ");
        }
    }
}
