<?php

namespace Shop\Fruit\Tests\Units\CommandBuilder;

use Shop\Fruit\Application\Command\Save\SaveFruitCommand;

class SaveFruitCommandBuilder
{
    public string $referenceId;
    public string $name;
    public ?string $fruitId;

    /**
     * @return self
     */
    public static function asBuilder():self
    {
        $self = new self();
        $self->referenceId = 'someReferenceId';
        $self->name = 'some fruit name';
        $self->fruitId = null;
        return $self;
    }

    /**
     * @param string $fruitId
     * @return $this
     */
    public function withFruitId(string $fruitId): self
    {
        $this->fruitId = $fruitId;
        return $this;
    }

    /**
     * @param string $referenceId
     * @return $this
     */
    public function withReferenceId(string $referenceId):self
    {
        $this->referenceId = $referenceId;
        return $this;
    }

    /**
     * @param string $fruitName
     * @return self
     */
    public function withName(string $fruitName):self
    {
        $this->name = $fruitName;
        return $this;
    }

    /**
     * @return SaveFruitCommand
     */
    public function build(): SaveFruitCommand
    {
        return new SaveFruitCommand(
            referenceId:$this->referenceId,
            label: $this->name,
            fruitId: $this->fruitId
        );
    }
}
