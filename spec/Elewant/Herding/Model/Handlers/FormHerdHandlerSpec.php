<?php

namespace spec\Elewant\Herding\Model\Handlers;

use Elewant\Herding\Model\Command\FormHerd;
use Elewant\Herding\Model\Handlers\FormHerdHandler;
use Elewant\Herding\Model\HerdCollection;
use PhpSpec\ObjectBehavior;

class FormHerdHandlerSpec extends ObjectBehavior
{
    function it_is_initializable(HerdCollection $herdCollection)
    {
        $this->beConstructedWith($herdCollection);
        $this->shouldHaveType(FormHerdHandler::class);
    }

    function it_handles_form_herd()
    {
        $command = new FormHerd;
        $this->__invoke($command);
    }
}
