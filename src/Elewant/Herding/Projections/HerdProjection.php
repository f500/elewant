<?php

declare(strict_types=1);

namespace Elewant\Herding\Projections;

use Elewant\Herding\Model\Events\BreedDesireWasEliminatedByHerd;
use Elewant\Herding\Model\Events\BreedWasDesiredByHerd;
use Elewant\Herding\Model\Events\ElePHPantWasAbandonedByHerd;
use Elewant\Herding\Model\Events\ElePHPantWasAdoptedByHerd;
use Elewant\Herding\Model\Events\HerdWasAbandoned;
use Elewant\Herding\Model\Events\HerdWasFormed;
use Elewant\Herding\Model\Events\HerdWasRenamed;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;

final class HerdProjection implements ReadModelProjection
{

    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->when(
                [
                    HerdWasFormed::class => function ($state, HerdWasFormed $event) {
                        /** @var HerdReadModel $readModel */
                        $readModel = $this->readModel();
                        $readModel->stack(
                            'onHerdWasFormed',
                            $event->herdId(),
                            $event->shepherdId(),
                            $event->name(),
                            $event->createdAt()
                        );
                    },
                    HerdWasRenamed::class => function($state, HerdWasRenamed $event) {
                        /** @var HerdReadModel $readModel */
                        $readModel = $this->readModel();
                        $readModel->stack(
                            'onHerdWasRenamed',
                            $event->herdId(),
                            $event->newHerdName()
                        );
                    },
                    ElePHPantWasAdoptedByHerd::class => function($state, ElePHPantWasAdoptedByHerd $event) {
                        /** @var HerdReadModel $readModel */
                        $readModel = $this->readModel();
                        $readModel->stack(
                            'onElePHPantWasAdoptedByHerd',
                            $event->elePHPantId(),
                            $event->herdId(),
                            $event->breed(),
                            $event->createdAt()
                        );
                    },
                    ElePHPantWasAbandonedByHerd::class => function($state, ElePHPantWasAbandonedByHerd $event) {
                        /** @var HerdReadModel $readModel */
                        $readModel = $this->readModel();
                        $readModel->stack(
                            'onElePHPantWasAbandonedByHerd',
                            $event->elePHPantId()
                        );
                    },
                    BreedWasDesiredByHerd::class => function($state, BreedWasDesiredByHerd $event) {
                        /** @var HerdReadModel $readModel */
                        $readModel = $this->readModel();
                        $readModel->stack(
                            'onBreedWasDesiredByHerd',
                            $event->herdId(),
                            $event->breed(),
                            $event->createdAt()
                        );
                    },
                    BreedDesireWasEliminatedByHerd::class => function($state, BreedDesireWasEliminatedByHerd $event) {
                        /** @var HerdReadModel $readModel */
                        $readModel = $this->readModel();
                        $readModel->stack(
                            'onBreedDesireWasEliminatedByHerd',
                            $event->herdId(),
                            $event->breed(),
                            $event->createdAt()
                        );
                    },
                    HerdWasAbandoned::class => function($state, HerdWasAbandoned $event) {
                        /** @var HerdReadModel $readModel */
                        $readModel = $this->readModel();
                        $readModel->stack(
                            'onHerdWasAbandoned',
                            $event->herdId()
                        );
                    },
                ]
            );

        return $projector;
    }

}
