<?php

namespace spec\Elewant\Herding\Model\Events;

use Elewant\Herding\Model\Events\HerdWasAbandoned;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use PhpSpec\ObjectBehavior;

class HerdWasAbandonedSpec extends ObjectBehavior
{
    function it_took_place()
    {
        $herdId     = HerdId::fromString('00000000-0000-0000-0000-000000000000');
        $shepherdId = ShepherdId::fromString('10000000-0000-0000-0000-000000000000');

        $this->beConstructedThrough(
            'tookPlace',
            [
                $herdId,
                $shepherdId,
            ]
        );

        $this->shouldHaveType(HerdWasAbandoned::class);
        $this->aggregateId()->shouldReturn($herdId->toString());
        $this->herdId()->shouldEqual($herdId);
        $this->shepherdId()->shouldEqual($shepherdId);
    }
}
