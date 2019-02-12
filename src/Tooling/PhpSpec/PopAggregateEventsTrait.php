<?php

declare(strict_types=1);

namespace Tooling\PhpSpec;

use ArrayIterator;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;

trait PopAggregateEventsTrait
{
    /**
     * @var AggregateTranslator
     */
    private $aggregateTranslator;

    /**
     * @param AggregateRoot $aggregateRoot
     * @return AggregateChanged[]
     */
    protected function popRecordedEvent(AggregateRoot $aggregateRoot): array
    {
        return $this->getAggregateTranslator()->extractPendingStreamEvents($aggregateRoot);
    }

    /**
     * @param string $aggregateRootClass
     * @param AggregateChanged[] $events
     * @return AggregateRoot
     */
    protected function reconstituteAggregateFromHistory(string $aggregateRootClass, array $events): object
    {
        return $this->getAggregateTranslator()->reconstituteAggregateFromHistory(
            AggregateType::fromAggregateRootClass($aggregateRootClass),
            new ArrayIterator($events)
        );
    }

    private function getAggregateTranslator(): AggregateTranslator
    {
        if ($this->aggregateTranslator === null) {
            $this->aggregateTranslator = new AggregateTranslator();
        }

        return $this->aggregateTranslator;
    }
}
