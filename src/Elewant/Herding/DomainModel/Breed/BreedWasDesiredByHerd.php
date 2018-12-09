<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Breed;

use Elewant\Herding\DomainModel\Herd\HerdId;
use Prooph\EventSourcing\AggregateChanged;

final class BreedWasDesiredByHerd extends AggregateChanged
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
        /** @noinspection PhpUnhandledExceptionInspection */
        return HerdId::fromString($this->aggregateId());
    }

    public function breed(): Breed
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return Breed::fromString($this->payload['breed']);
    }
}
