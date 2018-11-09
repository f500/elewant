<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Herd;

use Prooph\EventSourcing\AggregateChanged;

final class HerdWasRenamed extends AggregateChanged
{
    public static function tookPlace(HerdId $herdId, string $newHerdName): self
    {
        return self::occur(
            $herdId->toString(),
            [
                'newHerdName' => $newHerdName,
            ]
        );
    }

    public function herdId(): HerdId
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return HerdId::fromString($this->aggregateId());
    }

    public function newHerdName(): string
    {
        return $this->payload['newHerdName'];
    }
}
