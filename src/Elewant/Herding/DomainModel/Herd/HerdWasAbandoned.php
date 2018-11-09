<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Herd;

use Elewant\Herding\DomainModel\ShepherdId;
use Prooph\EventSourcing\AggregateChanged;

final class HerdWasAbandoned extends AggregateChanged
{
    public static function tookPlace(HerdId $herdId, ShepherdId $shepherdId): self
    {
        return self::occur(
            $herdId->toString(),
            [
                'shepherdId' => $shepherdId->toString(),
            ]
        );
    }

    public function herdId(): HerdId
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return HerdId::fromString($this->aggregateId());
    }

    public function shepherdId(): ShepherdId
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return ShepherdId::fromString($this->payload['shepherdId']);
    }
}
