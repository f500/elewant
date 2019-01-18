<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\ElePHPant;

use Elewant\Herding\DomainModel\Breed\Breed;

final class ElePHPant
{
    /** @var ElePHPantId */
    private $elePHPantId;

    /** @var Breed */
    private $breed;

    private function __construct(ElePHPantId $elePHPantId, Breed $breed)
    {
        $this->elePHPantId = $elePHPantId;
        $this->breed = $breed;
    }

    public static function appear(ElePHPantId $elePHPantId, Breed $breed): self
    {
        return new self($elePHPantId, $breed);
    }

    public function elePHPantId(): ElePHPantId
    {
        return $this->elePHPantId;
    }

    public function breed(): Breed
    {
        return $this->breed;
    }
}
