<?php /** @noinspection PhpUnusedParameterInspection */

declare(strict_types=1);

namespace Elewant\Webapp\Infrastructure\ProophProjections;

/**
 * Herding events are okay to use!
 */

use Elewant\Herding\DomainModel\Breed\BreedDesireWasEliminatedByHerd;
use Elewant\Herding\DomainModel\Breed\BreedWasDesiredByHerd;
use Elewant\Herding\DomainModel\ElePHPant\ElePHPantWasAbandonedByHerd;
use Elewant\Herding\DomainModel\ElePHPant\ElePHPantWasAdoptedByHerd;
use Elewant\Herding\DomainModel\Herd\HerdWasAbandoned;
use Elewant\Herding\DomainModel\Herd\HerdWasFormed;
use Elewant\Herding\DomainModel\Herd\HerdWasRenamed;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;

final class HerdProjection implements ReadModelProjection
{

    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->when(
                [
                    HerdWasFormed::class                  =>
                        function (array $state, HerdWasFormed $event): void {
                            /** @var ReadModelProjector $projector */
                            $projector = $this;
                            /** @var HerdReadModel $readModel */
                            $readModel = $projector->readModel();

                            $readModel->stack(
                                'onHerdWasFormed',
                                $event->herdId(),
                                $event->shepherdId(),
                                $event->name(),
                                $event->createdAt()
                            );
                        },
                    HerdWasRenamed::class                 =>
                        function (array $state, HerdWasRenamed $event): void {
                            /** @var ReadModelProjector $projector */
                            $projector = $this;
                            /** @var HerdReadModel $readModel */
                            $readModel = $projector->readModel();

                            $readModel->stack(
                                'onHerdWasRenamed',
                                $event->herdId(),
                                $event->newHerdName()
                            );
                        },
                    ElePHPantWasAdoptedByHerd::class      =>
                        function (array $state, ElePHPantWasAdoptedByHerd $event): void {
                            /** @var ReadModelProjector $projector */
                            $projector = $this;
                            /** @var HerdReadModel $readModel */
                            $readModel = $projector->readModel();

                            $readModel->stack(
                                'onElePHPantWasAdoptedByHerd',
                                $event->elePHPantId(),
                                $event->herdId(),
                                $event->breed(),
                                $event->createdAt()
                            );
                        },
                    ElePHPantWasAbandonedByHerd::class    =>
                        function (array $state, ElePHPantWasAbandonedByHerd $event): void {
                            /** @var ReadModelProjector $projector */
                            $projector = $this;
                            /** @var HerdReadModel $readModel */
                            $readModel = $projector->readModel();

                            $readModel->stack(
                                'onElePHPantWasAbandonedByHerd',
                                $event->elePHPantId()
                            );
                        },
                    BreedWasDesiredByHerd::class          =>
                        function (array $state, BreedWasDesiredByHerd $event): void {
                            /** @var ReadModelProjector $projector */
                            $projector = $this;
                            /** @var HerdReadModel $readModel */
                            $readModel = $projector->readModel();

                            $readModel->stack(
                                'onBreedWasDesiredByHerd',
                                $event->herdId(),
                                $event->breed(),
                                $event->createdAt()
                            );
                        },
                    BreedDesireWasEliminatedByHerd::class =>
                        function (array $state, BreedDesireWasEliminatedByHerd $event): void {
                            /** @var ReadModelProjector $projector */
                            $projector = $this;
                            /** @var HerdReadModel $readModel */
                            $readModel = $projector->readModel();

                            $readModel->stack(
                                'onBreedDesireWasEliminatedByHerd',
                                $event->herdId(),
                                $event->breed(),
                                $event->createdAt()
                            );
                        },
                    HerdWasAbandoned::class               =>
                        function (array $state, HerdWasAbandoned $event): void {
                            /** @var ReadModelProjector $projector */
                            $projector = $this;
                            /** @var HerdReadModel $readModel */
                            $readModel = $projector->readModel();

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
