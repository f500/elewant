<?php

declare(strict_types=1);

namespace Elewant\Herding\Model\Events;

use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use Prooph\EventSourcing\AggregateChanged;

final class HerdWasAbandoned extends AggregateChanged
{
    public static function tookPlace(HerdId $herdId, ShepherdId $shepherdId): self
    {
        return self::occur($herdId->toString(), [
            'shepherdId' => $shepherdId->toString(),
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

}
