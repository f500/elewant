<?php

declare(strict_types=1);

namespace Elewant\Domain;

use Assert\Assert;
use Assert\Assertion;
use Elewant\Domain\Events\ElePHPantHasJoinedHerd;
use Elewant\Domain\Events\ElePHPantHasLeftHerd;
use Elewant\Domain\Events\HerdWasFormed;
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

    public static function form(ShepherdId $shepherdId): self
    {
        $herdId = HerdId::generate();

        $instance = new self();

        $instance->recordThat(HerdWasFormed::tookPlace($herdId, $shepherdId));
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

    public function embraceElePHPant(string $elePHPantType): void
    {
        $this->recordThat(ElePHPantHasJoinedHerd::tookPlace(
            $this->herdId,
            ElePHPantId::generate(),
            $elePHPantType
        ));
    }

    public function abandonElePHPant(string $elePHPantType)
    {
        foreach ($this->elePHPants as $elePHPant) {
            if ($elePHPant->type() === $elePHPantType) {
                $this->recordThat(ElePHPantHasLeftHerd::tookPlace(
                    $this->herdId,
                    $elePHPant->elePHPantId(),
                    $elePHPantType
                ));

                return;
            }
        }

        throw SorryIDoNotHaveThat::typeOfElePHPant($this, $elePHPantType);
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
                $this->applyHerdWasFormed($event->herdId(), $event->shepherdId());
                break;
            case ElePHPantHasJoinedHerd::class:
                /** @var ElePHPantHasJoinedHerd $event */
                $this->applyAnElePHPantHasJoinedHerd($event->herdId(), $event->elePHPantId(), $event->elePHPantType());
                break;
            case ElePHPantHasLeftHerd::class:
                /** @var ElePHPantHasLeftHerd $event */
                $this->applyAnElePHPantHasLeftHerd($event->herdId(), $event->elePHPantId(), $event->elePHPantType());
                break;
            default:
                throw SorryIDontKnowThat::event($this, $event);
        }
    }

    private function applyHerdWasFormed(HerdId $herdId, ShepherdId $shepherdId): void
    {
        $this->herdId = $herdId;
        $this->shepherdId = $shepherdId;
    }

    private function applyAnElePHPantHasJoinedHerd(HerdId $herdId, ElePHPantId $elePHPantId, string $elePHPantType): void
    {
        $this->elePHPants[] = ElePHPant::appear($elePHPantId, $elePHPantType);
    }

    private function applyAnElePHPantHasLeftHerd(HerdId $herdId, ElePHPantId $elePHPantId, string $elePHPantType): void
    {
        foreach ($this->elePHPants as $key => $elePHPant) {
            if ($elePHPant->elePHPantId()->equals($elePHPantId)) {
                unset($this->elePHPants[$key]);
            }
        }
    }

}
