<?php

namespace Shop\Basket\Infrastructure;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shop\Basket\Domain\Basket as DomainBasket;
use Shop\Basket\Domain\Enums\BasketStatus;
use Shop\Basket\Infrastructure\database\Factory\BasketFactory;
use Shop\Reference\Infrastructure\Model\TheReference;
use Shop\shared\DateVo;
use Shop\shared\Id;

class Basket extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function the_references(): BelongsToMany
    {
        return $this->belongsToMany(TheReference::class);
    }

    protected static function newFactory(): BasketFactory
    {
        return BasketFactory::new();
    }


    public function toDomain(): DomainBasket
    {
        $basket = new DomainBasket(
            id: new Id($this->id),
            createdAt: new DateVo($this->created_at)
        );
        $basket->changeStatus(BasketStatus::in($this->status));
        $basket->changeUpdatedAt(new DateVo($this->updatedAt));

        return $basket;
    }
}
