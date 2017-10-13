<?php

declare(strict_types=1);

namespace Elewant\Herding\Model\Events;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\ElePHPantId;
use Elewant\Herding\Model\HerdId;
use Prooph\EventSourcing\AggregateChanged;

class ElePHPantWasAbandonedByHerd extends AggregateChanged
{
    public static function tookPlace(HerdId $herdId, ElePHPantId $elePHPantId, Breed $breed): self
    {
        return self::occur(
            $herdId->toString(),
            [
                'elePHPantId' => $elePHPantId->toString(),
                'breed'       => $breed->toString(),
            ]
        );
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
        return Breed::fromString($this->payload['breed']);
    }
}
