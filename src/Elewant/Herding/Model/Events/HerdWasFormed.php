<?php

declare(strict_types=1);

namespace Elewant\Herding\Model\Events;

use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use Prooph\EventSourcing\AggregateChanged;

final class HerdWasFormed extends AggregateChanged
{
    public static function tookPlace(HerdId $herdId, ShepherdId $shepherdId, string $name): self
    {
        return self::occur($herdId->toString(), [
            'shepherdId' => $shepherdId->toString(),
            'name' => $name
        ]);
    }

    public function herdId(): HerdId
    {
        return HerdId::fromString($this->aggregateId());
    }

    public function shepherdId(): ShepherdId
    {
        return ShepherdId::fromString($this->payload['shepherdId']);
    }

    public function name()
    {
        return $this->payload['name'];
    }
}
