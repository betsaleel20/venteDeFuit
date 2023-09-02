<?php

namespace Shop\Fruit\Infrastructure\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shop\Fruit\Infrastructure\database\Factory\FruitFactory;
use Shop\Reference\Infrastructure\Model\TheReference;
use Shop\Fruit\Domain\Fruit as DomainFruit;
use Shop\shared\DateVo;
use Shop\shared\Id;
use Shop\shared\StringVo;

class Fruit extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function newFactory(): FruitFactory
    {
        return FruitFactory::new();
    }
    public function productReferences(): BelongsTo
    {
        return $this->belongsTo(TheReference::class);
    }

    public function toDomain(): DomainFruit
    {
        $fruit = DomainFruit::create(
            referenceId: new Id($this->product_reference_id),
            fruitName: new StringVo($this->fruit_name),
            fruitId: new Id($this->id),
            createdAt: new DateVo($this->created_at)
        );
        $fruit->updatedAt(new DateVo($this->updated_at));
        return $fruit;
    }
}
