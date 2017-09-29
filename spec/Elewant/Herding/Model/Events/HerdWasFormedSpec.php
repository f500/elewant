<?php

namespace spec\Elewant\Herding\Model\Events;

use Elewant\Herding\Model\Events\HerdWasFormed;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use PhpSpec\ObjectBehavior;

class HerdWasFormedSpec extends ObjectBehavior
{
    function it_took_place()
    {
        $herdId     = HerdId::fromString('00000000-0000-0000-0000-000000000000');
        $shepherdId = ShepherdId::fromString('00000000-0000-0000-0000-000000000001');
        $name       = 'Herd name';

        $this->beConstructedThrough(
            'tookPlace',
            [
                $herdId,
                $shepherdId,
                $name,
            ]
        );

        $this->shouldHaveType(HerdWasFormed::class);
        $this->aggregateId()->shouldReturn($herdId->toString());
        $this->herdId()->shouldEqual($herdId);
        $this->shepherdId()->shouldEqual($shepherdId);
        $this->name()->shouldEqual($name);
    }

}
