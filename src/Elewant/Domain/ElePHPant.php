<?php

declare(strict_types=1);

namespace Elewant\Domain;

final class ElePHPant
{
    const GREEN = 'green';
    const BLUE = 'blue';
    const RED = 'red';

    /**
     * @var ElePHPantId
     */
    private $elePHPantId;

    /**
     * @var string
     */
    private $type;

    private function __construct(ElePHPantId $elePHPantId, string $type)
    {
        $this->elePHPantId = $elePHPantId;
        $this->type = $type;
    }

    public static function appear(ElePHPantId $elePHPantId, string $type): self
    {
        return new self($elePHPantId, $type);
    }

    public function elePHPantId()
    {
        return $this->elePHPantId;
    }

    public function type()
    {
        return $this->type;
    }

}
