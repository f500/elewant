<?php

namespace spec\Elewant\Herding\Model\Command;

use Elewant\Herding\Model\Command\FormHerd;
use Elewant\Herding\Model\ShepherdId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormHerdSpec extends ObjectBehavior
{
    function it_is_initializable($shepherdId)
    {
        $this->beConstructedWith($shepherdId);
        $this->shouldHaveType(FormHerd::class);
    }
}
