<?php

namespace spec\Elewant\Herding\Model\Commands;

use Elewant\Herding\Application\Commands\RenameHerd;
use Elewant\Herding\Model\HerdId;
use PhpSpec\ObjectBehavior;

class RenameHerdSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedThrough(
            'forShepherd',
            [
                '00000000-0000-0000-0000-000000000000',
                'My shiny new herd name is: Ughghg',
            ]
        );
        $this->shouldHaveType(RenameHerd::class);
        $this->herdId()->shouldEqual(HerdId::fromString('00000000-0000-0000-0000-000000000000'));
        $this->newHerdName()->shouldBe('My shiny new herd name is: Ughghg');
    }
}
