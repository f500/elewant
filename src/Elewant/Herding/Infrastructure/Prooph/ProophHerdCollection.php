<?php

declare(strict_types=1);

namespace Elewant\Herding\Infrastructure\Prooph;

use Elewant\Herding\DomainModel\Herd\Herd;
use Elewant\Herding\DomainModel\Herd\HerdCollection;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

final class ProophHerdCollection extends AggregateRepository implements HerdCollection
{
    public function save(Herd $herd): void
    {
        $this->saveAggregateRoot($herd);
    }

    public function get(HerdId $herdId): ?Herd
    {
        /** @var Herd $herd */
        $herd = $this->getAggregateRoot($herdId->toString());

        return $herd;
    }
}
