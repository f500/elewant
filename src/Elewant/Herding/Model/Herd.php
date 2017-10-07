<?php

declare(strict_types=1);

namespace Elewant\Herding\Model;

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

    public function abandonElePHPant(Breed $breed)
    {
        $this->guardIsNotAbandoned();

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
                $this->applyAnElePHPantWasAdoptedByHerd($event->herdId(), $event->elePHPantId(), $event->breed());
                break;
            case ElePHPantWasAbandonedByHerd::class:
                /** @var ElePHPantWasAbandonedByHerd $event */
                $this->applyAnElePHPantWasAbandonedByHerd($event->herdId(), $event->elePHPantId(), $event->breed());
                break;
            case HerdWasRenamed::class:
                /** @var HerdWasRenamed $event */
                $this->applyHerdWasRenamed($event->newHerdName());
                break;
            case HerdWasAbandoned::class:
                /** @var HerdWasAbandoned $event */
                $this->applyHerdWasAbandoned($event->herdId(), $event->shepherdId());
                break;
            default:
                throw SorryIDontKnowThat::event($this, $event);
        }
    }

    private function applyHerdWasFormed(HerdId $herdId, ShepherdId $shepherdId, string $name): void
    {
        $this->herdId     = $herdId;
        $this->shepherdId = $shepherdId;
        $this->name       = $name;
    }

    private function applyAnElePHPantWasAdoptedByHerd(HerdId $herdId, ElePHPantId $elePHPantId, Breed $breed): void
    {
        $this->elePHPants[] = ElePHPant::appear($elePHPantId, $breed);
    }

    private function applyAnElePHPantWasAbandonedByHerd(HerdId $herdId, ElePHPantId $elePHPantId, Breed $breed): void
    {
        foreach ($this->elePHPants as $key => $elePHPant) {
            if ($elePHPant->elePHPantId()->equals($elePHPantId)) {
                unset($this->elePHPants[$key]);
            }
        }
    }

    private function applyHerdWasAbandoned($herdId, $shepherdId)
    {
        $this->abandoned = true;
    }

    private function applyHerdWasRenamed(string $newHerdName)
    {
        $this->name = $newHerdName;
    }

    private function guardIsNotAbandoned()
    {
        if ($this->abandoned) {
            throw SorryICanNotChangeHerd::becauseItWasAbandoned($this);
        }
    }
}
