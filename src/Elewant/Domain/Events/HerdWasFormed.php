<?php

declare(strict_types=1);

namespace Elewant\Domain\Events;

use Elewant\Domain\HerdId;
use Elewant\Domain\ShepherdId;
use Prooph\EventSourcing\AggregateChanged;

final class HerdWasFormed extends AggregateChanged
{
    public static function tookPlace(HerdId $herdId, ShepherdId $shepherdId): self
    {
        return self::occur($herdId->toString(), ['shepherdId' => $shepherdId->toString()]);
    }

    public function herdId(): HerdId
    {
        return HerdId::fromString($this->aggregateId());
    }

    public function shepherdId(): ShepherdId
    {
        return ShepherdId::fromString($this->payload['shepherdId']);
    }
}
