<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\ElePHPant;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Herd\HerdId;
use PhpSpec\ObjectBehavior;

final class ElePHPantWasAdoptedByHerdSpec extends ObjectBehavior
{
    public function it_took_place(): void
    {
        $herdId = HerdId::fromString('00000000-0000-0000-0000-000000000000');
        $elePHPantId = ElePHPantId::fromString('10000000-0000-0000-0000-000000000000');

        $this->beConstructedThrough(
            'tookPlace',
            [
                $herdId,
                $elePHPantId,
                Breed::fromString(Breed::BLUE_ORIGINAL_REGULAR),
            ]
        );

        $this->shouldHaveType(ElePHPantWasAdoptedByHerd::class);
        $this->aggregateId()->shouldReturn($herdId->toString());
        $this->herdId()->shouldEqual($herdId);
        $this->elePHPantId()->shouldEqual($elePHPantId);
        $this->breed()->shouldEqual(Breed::fromString(Breed::BLUE_ORIGINAL_REGULAR));
    }
}
