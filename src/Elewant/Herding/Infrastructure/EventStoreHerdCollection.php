<?php

declare(strict_types=1);

namespace Elewant\Herding\Infrastructure;

use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\HerdId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

final class EventStoreHerdCollection extends AggregateRepository implements HerdCollection
{
    public function save(Herd $herd): void
    {
        $this->saveAggregateRoot($herd);
    }

    public function get(HerdId $herdId): ?Herd
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getAggregateRoot($herdId->toString());
    }
}
