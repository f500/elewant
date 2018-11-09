<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Herd;

use Elewant\Herding\DomainModel\ShepherdId;
use Prooph\EventSourcing\AggregateChanged;

final class HerdWasFormed extends AggregateChanged
{
    public static function tookPlace(HerdId $herdId, ShepherdId $shepherdId, string $name): self
    {
        return self::occur(
            $herdId->toString(),
            [
                'shepherdId' => $shepherdId->toString(),
                'name'       => $name,
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

    public function name(): string
    {
        return $this->payload['name'];
    }
}
