<?php

declare(strict_types=1);

namespace Elewant\Herding\Model\Events;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\HerdId;
use Prooph\EventSourcing\AggregateChanged;

class BreedDesireWasEliminatedByHerd extends AggregateChanged
{
    public static function tookPlace(HerdId $herdId, Breed $breed): self
    {
        return self::occur(
            $herdId->toString(),
            [
                'breed' => $breed->toString(),
            ]
        );
    }

    public function herdId(): HerdId
    {
        return HerdId::fromString($this->aggregateId());
    }

    public function breed(): Breed
    {
        return Breed::fromString($this->payload['breed']);
    }
}
