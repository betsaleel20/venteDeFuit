<?php

namespace Shop\Reference\Infrastructure\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shop\Basket\Infrastructure\Basket;
use Shop\Fruit\Domain\Fruit;
use Shop\Reference\Domain\TheReference as DomainReference;
use Shop\Reference\Infrastructure\Database\Factories\TheReferenceFactory;
use Shop\shared\DateVo;
use Shop\shared\Id;
use Shop\shared\PriceVo;
use Shop\shared\StringVo;

class TheReference extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $guarded = [];
    protected $primaryKey = "id";
    protected $keyType = "string";

    protected static function newFactory(): TheReferenceFactory
    {
        return TheReferenceFactory::new();
    }

    public function fruits(): HasMany
    {
        return $this->hasMany(Fruit::class);
    }

    public function baskets(): HasMany
    {
        return $this->hasMany(Basket::class);
    }

    /**
     * @return DomainReference
     */
    public function toDomain(): DomainReference
    {
        $domainReference = DomainReference::create(
            referenceName: new StringVo($this->the_reference_label),
            referencePrice: new PriceVo($this->the_reference_price),
            referenceId: new Id($this->id)
        );
        $domainReference->changeCreatedAt(new DateVo($this->created_at));
        $domainReference->changeDeletedAt(new DateVo($this->deleted_at));
        return $domainReference ;
    }
}
