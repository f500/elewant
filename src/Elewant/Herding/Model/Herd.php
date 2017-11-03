<?php

declare(strict_types=1);

namespace Elewant\Herding\Model;

use Elewant\Herding\Model\Events\BreedDesireWasEliminatedByHerd;
use Elewant\Herding\Model\Events\BreedWasDesiredByHerd;
use Elewant\Herding\Model\Events\ElePHPantWasAbandonedByHerd;
use Elewant\Herding\Model\Events\ElePHPantWasAdoptedByHerd;
use Elewant\Herding\Model\Events\HerdWasAbandoned;
use Elewant\Herding\Model\Events\HerdWasFormed;
use Elewant\Herding\Model\Events\HerdWasRenamed;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

final class Herd extends AggregateRoot
{
    /** @var HerdId */
    private $herdId;

    /** @var ShepherdId */
    private $shepherdId;

    /** @var ElePHPant[] */
    private $elePHPants = [];

    /** @var bool */
    private $abandoned = false;

    /** @var  string */
    private $name;

    /** @var  BreedCollection */
    private $breeds;

    /** @var  BreedCollection */
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

    public function abandon()
    {
        $this->guardIsNotAbandoned();

        $this->recordThat(
            HerdWasAbandoned::tookPlace(
                $this->herdId,
                $this->shepherdId
            )
        );
    }

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

    public function abandonElePHPant(Breed $breed): void
    {
        $this->guardIsNotAbandoned();
        $this->guardContainsThisBreed($breed);

        foreach ($this->elePHPants as $elePHPant) {
            if ($elePHPant->breed()->equals($breed)) {
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

        throw SorryIDoNotHaveThat::typeOfElePHPant($this, $breed);
    }

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
                throw SorryIDontKnowThat::event($this, $event);
        }
    }

    private function applyHerdWasFormed(HerdId $herdId, ShepherdId $shepherdId, string $name): void
    {
        $this->herdId        = $herdId;
        $this->shepherdId    = $shepherdId;
        $this->name          = $name;
        $this->breeds        = BreedCollection::fromArray([]);
        $this->desiredBreeds = BreedCollection::fromArray([]);
    }

    private function applyAnElePHPantWasAdoptedByHerd(ElePHPantId $elePHPantId, Breed $breed): void
    {
        $this->breeds->add($breed);
        $this->elePHPants[] = ElePHPant::appear($elePHPantId, $breed);
    }

    private function applyAnElePHPantWasAbandonedByHerd(ElePHPantId $elePHPantId, Breed $breed): void
    {
        $this->breeds->remove($breed);
        foreach ($this->elePHPants as $key => $elePHPant) {
            if ($elePHPant->elePHPantId()->equals($elePHPantId)) {
                unset($this->elePHPants[$key]);
            }
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

    private function guardIsNotAbandoned()
    {
        if ($this->abandoned) {
            throw SorryICanNotChangeHerd::becauseItWasAbandoned($this);
        }
    }

    private function guardContainsThisBreed($breed)
    {
        return $this->breeds->contains($breed);
    }
}
