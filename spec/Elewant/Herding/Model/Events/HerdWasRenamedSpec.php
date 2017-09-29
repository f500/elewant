<?php

namespace spec\Elewant\Herding\Model\Events;

use Elewant\Herding\Model\Events\HerdWasRenamed;
use Elewant\Herding\Model\HerdId;
use PhpSpec\ObjectBehavior;

class HerdWasRenamedSpec extends ObjectBehavior
{
    function it_took_place()
    {
        $herdId = HerdId::fromString('00000000-0000-0000-0000-000000000000');

        $this->beConstructedThrough(
            'tookPlace',
            [
                $herdId,
                'new herd name',
            ]
        );

        $this->shouldHaveType(HerdWasRenamed::class);
        $this->aggregateId()->shouldReturn($herdId->toString());
        $this->herdId()->shouldEqual($herdId);
        $this->newHerdName()->shouldEqual('new herd name');
    }
}
