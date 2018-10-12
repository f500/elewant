<?php

namespace spec\Elewant\Herding\Model\Commands;

use Elewant\Herding\Application\Commands\AbandonHerd;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use PhpSpec\ObjectBehavior;

class AbandonHerdSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedThrough(
            'forShepherd',
            [
                '00000000-0000-0000-0000-000000000000',
                '10000000-0000-0000-0000-000000000000',
            ]
        );
        $this->shouldHaveType(AbandonHerd::class);
        $this->herdId()->shouldEqual(HerdId::fromString('00000000-0000-0000-0000-000000000000'));
        $this->shepherdId()->shouldEqual(ShepherdId::fromString('10000000-0000-0000-0000-000000000000'));
    }
}
