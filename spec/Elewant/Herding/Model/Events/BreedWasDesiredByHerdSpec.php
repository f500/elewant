<?php

namespace spec\Elewant\Herding\Model\Events;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Events\BreedWasDesiredByHerd;
use Elewant\Herding\Model\HerdId;
use PhpSpec\ObjectBehavior;

class BreedWasDesiredByHerdSpec extends ObjectBehavior
{
    function it_took_place()
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
