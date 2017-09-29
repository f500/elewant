<?php

namespace spec\Elewant\Herding\Model\Commands;

use Elewant\Herding\Model\Commands\FormHerd;
use Elewant\Herding\Model\ShepherdId;
use PhpSpec\ObjectBehavior;

class FormHerdSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedThrough(
            'forShepherd',
            [
                '00000000-0000-0000-0000-000000000000',
                'Herd is the word',
            ]
        );
        $this->shouldHaveType(FormHerd::class);
        $this->shepherdId()->shouldEqual(ShepherdId::fromString('00000000-0000-0000-0000-000000000000'));
        $this->herdName()->shouldBe('Herd is the word');
    }
}
