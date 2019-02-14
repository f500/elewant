<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Herd;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Breed\BreedCollection;
use Elewant\Herding\DomainModel\Breed\BreedDesireWasEliminatedByHerd;
use Elewant\Herding\DomainModel\Breed\BreedWasDesiredByHerd;
use Elewant\Herding\DomainModel\ElePHPant\ElePHPant;
use Elewant\Herding\DomainModel\ElePHPant\ElePHPantId;
use Elewant\Herding\DomainModel\ElePHPant\ElePHPantWasAbandonedByHerd;
use Elewant\Herding\DomainModel\ElePHPant\ElePHPantWasAdoptedByHerd;
use Elewant\Herding\DomainModel\ShepherdId;
use Elewant\Herding\DomainModel\SorryICanNotChangeHerd;
use Elewant\Herding\DomainModel\SorryIDoNotHaveThat;
use Elewant\Herding\DomainModel\SorryIDoNotKnowThat;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

final class Herd extends AggregateRoot
{
    /**
     * @var HerdId
     */
    private $herdId;

    /**
     * @var ShepherdId
     */
    private $shepherdId;

    /**
     * @var ElePHPant[]
     */
    private $elePHPants = [];

    /**
     * @var bool
     */
    private $abandoned = false;

    /**
     * @var string
     */
    private $name;

    /**
     * @var BreedCollection
     */
    private $breeds;

    /**
     * @var BreedCollection
     */
    private $desiredBreeds;

    public static function form(ShepherdId $shepherdId, string $name): self
    {
        $herdId = HerdId::generate();

        $instance = new self();
        $instance->recordThat(HerdWasFormed::tookPlace($herdId, $shepherdId, $name));

        return $instance;
    }

    public function herdId(): HerdId
    {
        return $this->herdId;
    }

    public function shepherdId(): ShepherdId
    {
        return $this->shepherdId;
    }

    /**
     * @return ElePHPant[]
     */
    public function elePHPants(): array
    {
        return $this->elePHPants;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function breeds(): BreedCollection
    {
        return $this->breeds;
    }

    public function desiredBreeds(): BreedCollection
    {
        return $this->desiredBreeds;
    }

    public function isAbandoned(): bool
    {
        return $this->abandoned;
    }

    /**
     * @throws SorryICanNotChangeHerd
     */
    public function abandon(): void
    {
        $this->guardIsNotAbandoned();

        $this->recordThat(
            HerdWasAbandoned::tookPlace(
                $this->herdId,
                $this->shepherdId
            )
        );
    }

    /**
     * @param string $newName
     * @throws SorryICanNotChangeHerd
     */
    public function rename(string $newName): void
    {
        $this->guardIsNotAbandoned();

        $this->recordThat(
            HerdWasRenamed::tookPlace(
                $this->herdId,
                $newName
            )
        );
    }

    /**
     * @param Breed $breed
     * @throws SorryICanNotChangeHerd
     */
    public function adoptElePHPant(Breed $breed): void
    {
        $this->guardIsNotAbandoned();

        $this->recordThat(
            ElePHPantWasAdoptedByHerd::tookPlace(
                $this->herdId,
                ElePHPantId::generate(),
                $breed
            )
        );
    }

    /**
     * @param Breed $breed
     * @throws SorryICanNotChangeHerd
     * @throws SorryIDoNotHaveThat
     */
    public function abandonElePHPant(Breed $breed): void
    {
        $this->guardIsNotAbandoned();
        $this->guardContainsThisBreed($breed);

        foreach ($this->elePHPants as $elePHPant) {
            if (!$elePHPant->breed()->equals($breed)) {
                continue;
            }

            $this->recordThat(
                ElePHPantWasAbandonedByHerd::tookPlace(
                    $this->herdId,
                    $elePHPant->elePHPantId(),
                    $breed
                )
            );

            return;
        }
    }

    /**
     * @param Breed $breed
     * @throws SorryICanNotChangeHerd
     */
    public function desireBreed(Breed $breed): void
    {
        $this->guardIsNotAbandoned();

        $this->recordThat(
            BreedWasDesiredByHerd::tookPlace(
                $this->herdId,
                $breed
            )
        );
    }

    /**
     * @param Breed $breed
     * @throws SorryICanNotChangeHerd
     */
    public function eliminateDesireForBreed(Breed $breed): void
    {
        $this->guardIsNotAbandoned();

        $this->recordThat(
            BreedDesireWasEliminatedByHerd::tookPlace(
                $this->herdId,
                $breed
            )
        );
    }

    protected function aggregateId(): string
    {
        return $this->herdId->toString();
    }

    /**
     * @param AggregateChanged $event
     * @throws SorryIDoNotKnowThat
     */
    protected function apply(AggregateChanged $event): void
    {
        switch (get_class($event)) {
            case HerdWasFormed::class:
                /** @var HerdWasFormed $event */
                $this->applyHerdWasFormed($event->herdId(), $event->shepherdId(), $event->name());
                break;
            case ElePHPantWasAdoptedByHerd::class:
                /** @var ElePHPantWasAdoptedByHerd $event */
                $this->applyAnElePHPantWasAdoptedByHerd($event->elePHPantId(), $event->breed());
                break;
            case ElePHPantWasAbandonedByHerd::class:
                /** @var ElePHPantWasAbandonedByHerd $event */
                $this->applyAnElePHPantWasAbandonedByHerd($event->elePHPantId(), $event->breed());
                break;
            case HerdWasRenamed::class:
                /** @var HerdWasRenamed $event */
                $this->applyHerdWasRenamed($event->newHerdName());
                break;
            case HerdWasAbandoned::class:
                /** @var HerdWasAbandoned $event */
                $this->applyHerdWasAbandoned();
                break;
            case BreedWasDesiredByHerd::class:
                /** @var BreedWasDesiredByHerd $event */
                $this->applyBreedWasDesiredByHerd($event->breed());
                break;
            case BreedDesireWasEliminatedByHerd::class:
                /** @var BreedDesireWasEliminatedByHerd $event */
                $this->applyBreedDesireWasEliminatedByHerd($event->breed());
                break;
            default:
                throw SorryIDoNotKnowThat::event($this, $event);
        }
    }

    private function applyHerdWasFormed(HerdId $herdId, ShepherdId $shepherdId, string $name): void
    {
        $this->herdId = $herdId;
        $this->shepherdId = $shepherdId;
        $this->name = $name;
        $this->breeds = BreedCollection::fromArray([]);
        $this->desiredBreeds = BreedCollection::fromArray([]);
    }

    private function applyAnElePHPantWasAdoptedByHerd(ElePHPantId $elePHPantId, Breed $breed): void
    {
        if ($breed->isUnknown()) {
            return;
        }

        $this->breeds->add($breed);
        $this->elePHPants[] = ElePHPant::appear($elePHPantId, $breed);
    }

    private function applyAnElePHPantWasAbandonedByHerd(ElePHPantId $elePHPantId, Breed $breed): void
    {
        $this->breeds->remove($breed);

        foreach ($this->elePHPants as $key => $elePHPant) {
            if (!$elePHPant->elePHPantId()->equals($elePHPantId)) {
                continue;
            }

            unset($this->elePHPants[$key]);
        }
    }

    private function applyHerdWasAbandoned(): void
    {
        $this->abandoned = true;
    }

    private function applyHerdWasRenamed(string $newHerdName): void
    {
        $this->name = $newHerdName;
    }

    private function applyBreedWasDesiredByHerd(Breed $breed): void
    {
        $this->desiredBreeds->add($breed);
    }

    private function applyBreedDesireWasEliminatedByHerd(Breed $breed): void
    {
        $this->desiredBreeds->remove($breed);
    }

    /**
     * @throws SorryICanNotChangeHerd
     */
    private function guardIsNotAbandoned(): void
    {
        if ($this->abandoned) {
            throw SorryICanNotChangeHerd::becauseItWasAbandoned($this);
        }
    }

    /**
     * @param Breed $breed
     * @throws SorryIDoNotHaveThat
     */
    private function guardContainsThisBreed(Breed $breed): void
    {
        if ($breed->isUnknown()) {
            return;
        }

        foreach ($this->elePHPants() as $elePHPant) {
            if ($breed->equals($elePHPant->breed())) {
                return;
            }
        }

        throw SorryIDoNotHaveThat::typeOfElePHPant($this, $breed);
    }
}
