<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\ElePHPant;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Prooph\EventSourcing\AggregateChanged;

final class ElePHPantWasAbandonedByHerd extends AggregateChanged
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
        /** @noinspection PhpUnhandledExceptionInspection */
        return HerdId::fromString($this->aggregateId());
    }

    public function elePHPantId(): ElePHPantId
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return ElePHPantId::fromString($this->payload['elePHPantId']);
    }

    public function breed(): Breed
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return Breed::fromString($this->payload['breed']);
    }
}
