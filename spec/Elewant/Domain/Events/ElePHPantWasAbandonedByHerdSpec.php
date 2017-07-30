<?php

namespace spec\Elewant\Domain\Events;

use Elewant\Domain\Breed;
use Elewant\Domain\ElePHPantId;
use Elewant\Domain\Events\ElePHPantWasAbandonedByHerd;
use Elewant\Domain\HerdId;
use PhpSpec\ObjectBehavior;

class ElePHPantWasAbandonedByHerdSpec extends ObjectBehavior
{
    function it_took_place()
    {
        $herdId = HerdId::fromString('00000000-0000-0000-0000-000000000000');
        $elePHPantId = ElePHPantId::fromString('10000000-0000-0000-0000-000000000000');

        $this->beConstructedThrough('tookPlace', [
            $herdId,
            $elePHPantId,
            Breed::fromString(Breed::BLUE)
        ]);

        $this->shouldHaveType(ElePHPantWasAbandonedByHerd::class);
        $this->aggregateId()->shouldReturn($herdId->toString());
        $this->herdId()->shouldEqual($herdId);
        $this->elePHPantId()->shouldEqual($elePHPantId);
        $this->breed()->shouldEqual(Breed::fromString(Breed::BLUE));
    }
}
