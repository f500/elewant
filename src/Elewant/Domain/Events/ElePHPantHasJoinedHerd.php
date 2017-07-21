<?php

declare(strict_types=1);

namespace Elewant\Domain\Events;

use Elewant\Domain\ElePHPantId;
use Elewant\Domain\HerdId;
use Prooph\EventSourcing\AggregateChanged;

class ElePHPantHasJoinedHerd extends AggregateChanged
{
    public static function tookPlace(HerdId $herdId, ElePHPantId $elePHPantId, $elePHPantType) : self
    {
        return self::occur($herdId->toString(), [
            'elePHPantId' => $elePHPantId->toString(),
            'elePHPantType' => $elePHPantType
        ]);
    }

    public function herdId() : HerdId
    {
        return HerdId::fromString($this->aggregateId());
    }

    public function elePHPantId() : ElePHPantId
    {
        return ElePHPantId::fromString($this->payload['elePHPantId']);
    }

    public function elePHPantType() : string
    {
        return $this->payload['elePHPantType'];
    }

}
