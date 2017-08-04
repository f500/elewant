<?php

namespace spec\Elewant\Herding\Model\Handlers;

use Elewant\Herding\Model\Command\FormHerd;
use Elewant\Herding\Model\Handlers\FormHerdHandler;
use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\HerdCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormHerdHandlerSpec extends ObjectBehavior
{
    /** @var HerdCollection */
    private $herdCollection;

    function let(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
        $this->beConstructedWith($herdCollection);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FormHerdHandler::class);
    }

    function it_handles_form_herd()
    {
        $command = FormHerd::forShepherd('00000000-0000-0000-0000-000000000000', 'Herd is the word');

        $this->__invoke($command);

        $this->herdCollection->save(Argument::type(Herd::class))->shouldHaveBeenCalled();
    }
}
