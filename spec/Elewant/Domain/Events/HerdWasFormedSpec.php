<?php

namespace spec\Elewant\Domain\Events;

use Elewant\Domain\Events\HerdWasFormed;
use Elewant\Domain\HerdId;
use Elewant\Domain\ShepherdId;
use PhpSpec\ObjectBehavior;

class HerdWasFormedSpec extends ObjectBehavior
{
    function it_took_place()
    {
        $herdId = HerdId::fromString('00000000-0000-0000-0000-000000000000');
        $shepherdId = ShepherdId::fromString('00000000-0000-0000-0000-000000000001');

        $this->beConstructedThrough('tookPlace', [
            $herdId,
            $shepherdId
        ]);

        $this->shouldHaveType(HerdWasFormed::class);
        $this->aggregateId()->shouldReturn($herdId->toString());
        $this->herdId()->shouldEqual($herdId);
        $this->shepherdId()->shouldEqual($shepherdId);
    }

}
