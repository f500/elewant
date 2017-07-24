<?php

declare(strict_types=1);

namespace Elewant\Domain\Events;

use Elewant\Domain\Breed;
use Elewant\Domain\ElePHPantId;
use Elewant\Domain\HerdId;
use Prooph\EventSourcing\AggregateChanged;

class ElePHPantWasAbandonedByHerd extends AggregateChanged
{
    public static function tookPlace(HerdId $herdId, ElePHPantId $elePHPantId, Breed $breed): self
    {
        return self::occur($herdId->toString(), [
            'elePHPantId' => $elePHPantId->toString(),
            'breed' => $breed
        ]);
    }

    public function herdId(): HerdId
    {
        return HerdId::fromString($this->aggregateId());
    }

    public function elePHPantId(): ElePHPantId
    {
        return ElePHPantId::fromString($this->payload['elePHPantId']);
    }

    public function breed(): Breed
    {
        return $this->payload['breed'];
    }

}
