<?php

declare(strict_types=1);

namespace Elewant\Herding\Model\Events;

use Elewant\Herding\Model\HerdId;
use Prooph\EventSourcing\AggregateChanged;

class HerdWasRenamed extends AggregateChanged
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
        return HerdId::fromString($this->aggregateId());
    }

    public function newHerdName(): string
    {
        return $this->payload['newHerdName'];
    }

}
