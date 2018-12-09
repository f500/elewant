<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Breed;

use Elewant\Herding\DomainModel\Herd\HerdId;
use PhpSpec\ObjectBehavior;

final class BreedWasDesiredByHerdSpec extends ObjectBehavior
{
    public function it_took_place(): void
    {
        $herdId = HerdId::fromString('00000000-0000-0000-0000-000000000000');

        $this->beConstructedThrough(
            'tookPlace',
            [
                $herdId,
                Breed::fromString(Breed::BLUE_ORIGINAL_REGULAR),
            ]
        );

        $this->shouldHaveType(BreedWasDesiredByHerd::class);
        $this->aggregateId()->shouldReturn($herdId->toString());
        $this->herdId()->shouldEqual($herdId);
        $this->breed()->shouldEqual(Breed::fromString(Breed::BLUE_ORIGINAL_REGULAR));
    }
}
