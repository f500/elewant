<?php

declare(strict_types=1);

namespace Elewant\Herding\Model;

final class ElePHPant
{
    /**
     * @var ElePHPantId
     */
    private $elePHPantId;

    /**
     * @var Breed
     */
    private $type;

    private function __construct(ElePHPantId $elePHPantId, Breed $type)
    {
        $this->elePHPantId = $elePHPantId;
        $this->type = $type;
    }

    public static function appear(ElePHPantId $elePHPantId, Breed $type): self
    {
        return new self($elePHPantId, $type);
    }

    public function elePHPantId(): ElePHPantId
    {
        return $this->elePHPantId;
    }

    public function type(): Breed
    {
        return $this->type;
    }

}
