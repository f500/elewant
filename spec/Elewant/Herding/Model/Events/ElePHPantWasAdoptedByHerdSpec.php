<?php

namespace spec\Elewant\Herding\Model\Events;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\ElePHPantId;
use Elewant\Herding\Model\Events\ElePHPantWasAdoptedByHerd;
use Elewant\Herding\Model\HerdId;
use PhpSpec\ObjectBehavior;

class ElePHPantWasAdoptedByHerdSpec extends ObjectBehavior
{
    function it_took_place()
    {
        $herdId      = HerdId::fromString('00000000-0000-0000-0000-000000000000');
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
