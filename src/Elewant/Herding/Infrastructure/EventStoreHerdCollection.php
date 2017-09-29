<?php

declare(strict_types=1);

namespace Elewant\Herding\Infrastructure;

use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\HerdId;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\Aggregate\AggregateRepository;
use Prooph\EventStore\Aggregate\AggregateType;
use Prooph\EventStore\EventStore;

final class EventStoreHerdCollection extends AggregateRepository implements HerdCollection
{
    public function __construct(EventStore $eventStore)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromAggregateRootClass(Herd::class),
            new AggregateTranslator(),
            null,
            null,
            false
        );
    }

    public function save(Herd $herd): void
    {
        $this->addAggregateRoot($herd);
    }

    public function get(HerdId $herdId): ?Herd
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getAggregateRoot($herdId->toString());
    }
}
